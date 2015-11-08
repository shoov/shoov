module App where

import Dashboard exposing (Model, initialModel, update)
import Effects exposing (Effects)
import GithubAuth exposing (Model)
import Html exposing (..)
import Html.Attributes exposing (..)
import Html.Events exposing (onClick)
import Json.Encode as JE exposing (string, Value)
import Login exposing (Model, initialModel, update)
import Repo exposing (Model)
import RouteHash exposing (HashUpdate)
import Storage exposing (removeItem)
import String exposing (isEmpty)
import Task exposing (..)
import User exposing (..)

import Debug

-- MODEL

type alias AccessToken = String
type alias CompanyId = Int

type Page
  = Dashboard
  | GithubAuth
  | Login
  | User

type alias Model =
  { accessToken : AccessToken
  , activePage : Page
  , dashboard : Dashboard.Model
  , githubAuth: GithubAuth.Model
  , login: Login.Model
  -- If the user is anonymous, we want to know where to redirect them.
  , nextPage : Maybe Page

  , repos : Maybe (List Repo.Model)
  , user : User.Model
  }

initialModel : Model
initialModel =
  { accessToken = ""
  , activePage = Login
  , dashboard = Dashboard.initialModel
  , githubAuth = GithubAuth.initialModel
  , login = Login.initialModel
  , nextPage = Nothing
  , repos = Nothing
  , user = User.initialModel
  }

initialEffects : List (Effects Action)
initialEffects =
  let
    dashboardEffects = snd Dashboard.init
    githubAuthEffects = snd GithubAuth.init
    loginEffects = snd Login.init
    userEffects = snd User.init
  in
    [ Effects.map ChildDashboardAction dashboardEffects
    , Effects.map ChildGithubAuthAction githubAuthEffects
    , Effects.map ChildLoginAction loginEffects
    , Effects.map ChildUserAction userEffects
    ]

init : (Model, Effects Action)
init =
  ( initialModel
  , Effects.batch initialEffects
  )

-- UPDATE

type Action
  = ChildDashboardAction Dashboard.Action
  | ChildGithubAuthAction GithubAuth.Action
  | ChildLoginAction Login.Action
  | ChildUserAction User.Action
  | Logout
  -- Action to be called after a Logout
  | NoOp (Maybe ())
  | PostSetAccessToken (Result AccessToken ())
  | SetAccessToken AccessToken
  | SetActivePage Page
  | UpdateRepos (Maybe (List Repo.Model))

update : Action -> Model -> (Model, Effects Action)
update action model =
  case action of
    ChildDashboardAction act ->
      let
        (childModel, childEffects) = Dashboard.update act model.dashboard
      in
        ( {model | dashboard <- childModel }
        , Effects.map ChildDashboardAction childEffects
        )

    ChildGithubAuthAction act ->
      let
        (childModel, childEffects) = GithubAuth.update act model.githubAuth

        defaultEffect =
          Effects.map ChildGithubAuthAction childEffects

        -- A convinence variable to hold the default effect as a list.
        defaultEffects =
          [ defaultEffect ]

        effects' =
          case act of
            -- User's token was fetched, so we can set it in the accessToken
            -- root property, and also get the user info, which will in turn
            -- redirect the user from the login page.
            GithubAuth.SetAccessToken token ->
              (Task.succeed (SetAccessToken token) |> Effects.task)
              ::
              defaultEffects

            _ ->
              defaultEffects

      in
        ( {model | githubAuth <- childModel }
        , Effects.batch effects'
        )

    ChildLoginAction act ->
      let
        (childModel, childEffects) = Login.update act model.login

        defaultEffect =
          Effects.map ChildLoginAction childEffects

        -- A convinence variable to hold the default effect as a list.
        defaultEffects =
          [ defaultEffect ]

        effects' =
          case act of
            -- User's token was fetched, so we can set it in the accessToken
            -- root property, and also get the user info, which will in turn
            -- redirect the user from the login page.
            Login.SetAccessToken token ->
              (Task.succeed (SetAccessToken token) |> Effects.task)
              ::
              defaultEffects

            _ ->
              defaultEffects

      in
        ( { model | login <- childModel }
        , Effects.batch effects'
        )


    ChildUserAction act ->
      let
        context =
          { accessToken = model.accessToken }

        (childModel, childEffects) = User.update context act model.user

        defaultEffect =
          Effects.map ChildUserAction childEffects

        defaultEffects =
          [ defaultEffect ]

        model' =
          { model | user <- childModel }

        (model'', effects') =
          case act of
            User.SetAccessToken token ->
              ( model'
              , (Task.succeed (SetAccessToken token) |> Effects.task)
                ::
                defaultEffects
              )

            User.UpdateDataFromServer result ->
              case result of
                -- We reach out into the repos that is passed to the child
                -- action.
                Ok (_, _, repos) ->
                  let
                    nextPage =
                      case model.nextPage of
                        Just page ->
                          page
                        Nothing ->
                          Dashboard

                  in
                    -- User data was successfully fetched, so we can redirect to
                    -- the next page, and update their repos.
                    ( { model' | nextPage <- Nothing }
                    , (Task.succeed (UpdateRepos repos) |> Effects.task)
                      ::
                      (Task.succeed (SetActivePage nextPage) |> Effects.task)
                      ::
                      defaultEffects
                    )

                Err _ ->
                  ( model'
                  , defaultEffects
                  )

            _ ->
              ( model'
              , defaultEffects
              )

      in
        (model'', Effects.batch effects')


    Logout ->
      ( initialModel
      , Effects.batch <| removeStorageItem :: initialEffects
      )

    NoOp _ ->
      ( model, Effects.none )

    PostSetAccessToken result ->
      ( model, Effects.none )

    SetAccessToken accessToken ->
      let
        defaultEffects =
          [sendInputToStorage accessToken]

        effects' =
          if (String.isEmpty accessToken)
            then
              defaultEffects
            else
              (Task.succeed (ChildUserAction User.GetDataFromServer) |> Effects.task)
              ::
              defaultEffects

      in
      ( { model | accessToken <- accessToken}
      , Effects.batch effects'
      )

    SetActivePage page ->
      let
        (page', nextPage) =
          if model.user.name == Anonymous
            then
              -- If the user is anonymous and we are asked to set the  active
              -- page to login, then we make sure that the next page doesn't
              -- change, so they won't be rediected back to the login page.
              case page of
                Login ->
                  (Login, model.nextPage)

                GithubAuth ->
                  (GithubAuth, model.nextPage)

                _ ->
                  (Login, Just page)

            else (page, Nothing)

        currentPageEffects =
          case model.activePage of
            Dashboard ->
              Task.succeed (ChildDashboardAction Dashboard.Deactivate) |> Effects.task

            GithubAuth ->
              Task.succeed (ChildGithubAuthAction GithubAuth.Deactivate) |> Effects.task

            Login ->
              Task.succeed (ChildLoginAction Login.Deactivate) |> Effects.task

            User ->
              Task.succeed (ChildUserAction User.Deactivate) |> Effects.task



        newPageEffects =
          case page' of
            Dashboard ->
              Task.succeed (ChildDashboardAction Dashboard.Activate) |> Effects.task

            GithubAuth ->
              Task.succeed (ChildGithubAuthAction GithubAuth.Activate) |> Effects.task

            Login ->
              Task.succeed (ChildLoginAction Login.Activate) |> Effects.task

            User ->
              Task.succeed (ChildUserAction User.Activate) |> Effects.task


      in
        if model.activePage == page'
          then
            -- Requesting the same page, so don't do anything.
            -- @todo: Because login and myAccount are under the same page (User)
            -- we set the nextPage here as-well.
            ( { model | nextPage <- nextPage }, Effects.none)
          else
            ( { model
              | activePage <- page'
              , nextPage <- nextPage
              }
            , Effects.batch
              [ currentPageEffects
              , newPageEffects
              ]
            )

    UpdateRepos repos ->
      ( { model | repos <- repos }
      , Effects.none
      )

-- VIEW

view : Signal.Address Action -> Model -> Html
view address model =
  div []
    [ (navbar address model)
    , (mainContent address model)
    , footer
    ]

mainContent : Signal.Address Action -> Model -> Html
mainContent address model =
  case model.activePage of
    Dashboard ->
      let
        childAddress =
          Signal.forwardTo address ChildDashboardAction

      in
        div [ class "container" ] [ Dashboard.view childAddress model.dashboard ]

    GithubAuth ->
      let
        childAddress =
          Signal.forwardTo address ChildGithubAuthAction
      in
        div [ class "container" ] [ GithubAuth.view childAddress model.githubAuth ]

    Login ->
      let
        childAddress =
          Signal.forwardTo address ChildLoginAction
      in
        div [ class "container" ] [ Login.view childAddress model.login ]

    User ->
      let
        childAddress =
          Signal.forwardTo address ChildUserAction
      in
        div [ class "container" ] [ User.view childAddress model.user ]

navbar : Signal.Address Action -> Model -> Html
navbar address model =
  case model.user.name of
    Anonymous ->
      div [] []

    LoggedIn name ->
      navbarLoggedIn address model

footer : Html
footer =

  div [class "main-footer"]
    [ div [class "container"]
      [ span []
        [ text "With "
        , i [ class "fa fa-heart" ] []
        , text " from "
        , a [ href "http://gizra.com", target "_blank", class "gizra-logo" ] [text "gizra"]
        , span [ class "divider" ] [text "|"]
        , text "Fork me on "
        , a [href "https://github.com/Gizra/elm-hedley", target "_blank"] [text "Github"]
        ]
      ]
  ]

-- Navbar for Auth user.
navbarLoggedIn : Signal.Address Action -> Model -> Html
navbarLoggedIn address model =
  let
    childAddress =
      Signal.forwardTo address ChildUserAction

    hrefVoid =
      href "javascript:void(0);"
  in
    node "nav" [class "navbar navbar-default"]
      [ div [class "container-fluid"]
        -- Brand and toggle get grouped for better mobile display
          [ div [class "navbar-header"] []
          , div [ class "collapse navbar-collapse"]
              [ ul [class "nav navbar-nav"]
                [ li [] [ a [ hrefVoid, onClick address (SetActivePage User) ] [ text "My account"] ]
                , li [] [ a [ hrefVoid, onClick address (SetActivePage Dashboard)] [ text "Dashboard"] ]
                , li [] [ a [ hrefVoid, onClick address Logout] [ text "Logout"] ]
                ]
              ]
          ]
      ]


-- EFFECTS

sendInputToStorage : String -> Effects Action
sendInputToStorage val =
  Storage.setItem "access_token" (JE.string val)
    |> Task.toResult
    |> Task.map PostSetAccessToken
    |> Effects.task

-- Task to remove the access token from localStorage.
removeStorageItem : Effects Action
removeStorageItem =
  Storage.removeItem "access_token"
    |> Task.toMaybe
    |> Task.map NoOp
    |> Effects.task

-- ROUTING

delta2update : Model -> Model -> Maybe HashUpdate
delta2update previous current =
  case current.activePage of
    Dashboard ->
      -- First, we ask the submodule for a HashUpdate. Then, we use
      -- `map` to prepend something to the URL.
      RouteHash.map ((::) "") <|
        Dashboard.delta2update previous.dashboard current.dashboard

    GithubAuth ->
      RouteHash.map (\_ -> ["auth", "github"]) <|
        Login.delta2update previous.login current.login

    Login ->
      RouteHash.map ((::) "login") <|
        Login.delta2update previous.login current.login

    User ->
      RouteHash.map ((::) "my-account") <|
        User.delta2update previous.user current.user


-- Here, we basically do the reverse of what delta2update does
location2action : List String -> List Action
location2action list =
  case list of
    "" :: rest ->
      ( SetActivePage Dashboard ) :: []

    ["auth", "github"] ->
      ( SetActivePage GithubAuth ) :: []

    "login" :: rest ->
      ( SetActivePage Login ) :: []

    "my-account" :: rest ->
      ( SetActivePage User ) :: []

    _ ->
      -- @todo: Add 404
      ( SetActivePage Login ) :: []
