module Login where

import Config exposing (backendUrl)
import Effects exposing (Effects, Never)
import Html exposing (..)
import Html.Attributes exposing (..)
import Http
import Json.Decode as JD exposing ((:=))
import RouteHash exposing (HashUpdate)
import String exposing (isEmpty)
import Storage exposing (..)
import Task


import Debug


-- MODEL

type alias AccessToken = String

type Status =
  Init
  | Fetching
  | Fetched
  | HttpError Http.Error

type alias Model =
  { accessToken: AccessToken
  , private : Bool
  , status : Status
  , hasAccessTokenInStorage : Bool
  }

initialModel : Model
initialModel =
  { accessToken = ""
  , private = False
  , status = Init
  -- We start by assuming there's already an access token it the localStorage.
  -- While this property is set to True, the login form will not appear.
  , hasAccessTokenInStorage = True
  }


init : (Model, Effects Action)
init =
  ( initialModel
  -- Try to get an existing access token.
  , getInputFromStorage
  )


-- UPDATE

type Action
  = Activate
  | Deactivate
  | SetAccessToken AccessToken
  | UpdateAccessTokenFromServer (Result Http.Error AccessToken)
  | UpdateAccessTokenFromStorage (Result String AccessToken)

update : Action -> Model -> (Model, Effects Action)
update action model =
  case action of
    Activate ->
      (model, Effects.none)

    Deactivate ->
      (model, Effects.none)

    SetAccessToken accessToken ->
      ( { model | accessToken <- accessToken }
      , Effects.none
      )

    UpdateAccessTokenFromServer result ->
      case result of
        Ok token ->
          ( { model | status <- Fetched }
          , Task.succeed (SetAccessToken token) |> Effects.task
          )
        Err msg ->
          ( { model | status <- HttpError msg }
          , Effects.none
          )

    UpdateAccessTokenFromStorage result ->
      case result of
        Ok token ->
          if String.isEmpty token
            then
              ( { model | hasAccessTokenInStorage <- False }
              , Effects.none
              )
            else
              ( model
              , Task.succeed (SetAccessToken token) |> Effects.task
              )

        Err err ->
          -- There was no access token in the storage, so show the login form
          ( { model | hasAccessTokenInStorage <- False }
          , Effects.none
          )

getInputFromStorage : Effects Action
getInputFromStorage =
  Storage.getItem "access_token" JD.string
    |> Task.toResult
    |> Task.map UpdateAccessTokenFromStorage
    |> Effects.task


-- VIEW

view : Signal.Address Action -> Model -> Html
view address model =
  let
    repoScope =
      if model.private then "repo" else "public_repo"

    url =
      "https://github.com/login/oauth/authorize?client_id=" ++ Config.githubClientId ++ "&scope=user:email,read:org," ++ repoScope

    spinner =
      i [ class "fa fa-spinner fa-spin" ] []

    errorMessage =
      case model.status of
        HttpError err ->
          div [] [ text "There was some HTTP error"]
        _ ->
          div [] []

    content =
      if model.hasAccessTokenInStorage
        then
          spinner

        else
          div []
              [ a [ class "btn-lg btn-github text-center" , href url]
              [ i [ class "fa fa-github"] [] , text "With GitHub account"] ]
  in
  div
    [ id "dashboard-login" ]
    [ div
        [ class "login-wrapper"]
        [ div
            [ class "clearfix"]
            [ h1 [ class "pull-left"] [ text "Sign in"]
            , span [ class "shoov-logo pull-right"] [ text "Shoov"]
            ]
        , content
        , errorMessage 
    ]
  ]


-- ROUTER

delta2update : Model -> Model -> Maybe HashUpdate
delta2update previous current =
  Just <| RouteHash.set []

location2action : List String -> List Action
location2action list =
  []
