module User where

import Config exposing (backendUrl)
import Effects exposing (Effects, Never)
import Html exposing (..)
import Html.Attributes exposing (..)
import Http
import Json.Decode as Json exposing ((:=))
import Repo exposing (Model)
import RouteHash exposing (HashUpdate)
import String exposing (length)
import Task

import Debug

-- MODEL

type alias Id = Int
type alias AccessToken = String

type User = Anonymous | LoggedIn String

type Status =
  Init
  | Fetching
  | Fetched
  | HttpError Http.Error

type alias Model =
  { name : User
  , id : Id
  , status : Status
  , accessToken : AccessToken

  -- Child components
  , repos : Maybe (List Repo.Model)
  }


initialModel : Model
initialModel =
  { name = Anonymous
  , id = 0
  , status = Init
  , accessToken = ""

  -- Child components
  , repos = Nothing
  }

init : (Model, Effects Action)
init =
  ( initialModel
  , Effects.none
  )


-- UPDATE

type Action
  = NoOp (Maybe ())
  | GetDataFromServer
  | UpdateDataFromServer (Result Http.Error (Id, String, Maybe (List Repo.Model)))
  -- @todo: Remove, as we don't use it
  | SetAccessToken AccessToken

  -- Page
  | Activate
  | Deactivate

type alias Context =
  { accessToken : AccessToken}

update : Context -> Action -> Model -> (Model, Effects Action)
update context action model =
  case action of
    NoOp _ ->
      (model, Effects.none)

    GetDataFromServer ->
      let
        url : String
        url = Config.backendUrl ++ "/api/v1.0/me"
      in
        if model.status == Fetching || model.status == Fetched
          then
            (model, Effects.none)
          else
            ( { model | status <- Fetching }
            , getJson url context.accessToken
            )

    UpdateDataFromServer result ->
      let
        model' =
          { model | status <- Fetched}
      in
        case result of
          Ok (id, name, repos) ->
            ( {model'
                | id <- id
                , name <- LoggedIn name
                , repos <- repos
              }
            , Effects.none
            )
          Err msg ->
            let
              d = Debug.log "UpdateDataFromServer" msg
            in
            ( { model' | status <- HttpError msg }
            , Effects.none
            )

    SetAccessToken accessToken ->
      ( {model | accessToken <- accessToken}
      , Effects.none
      )

    Activate ->
      (model, Effects.none)

    Deactivate ->
      (model, Effects.none)


-- Determines if a call to the server should be done, based on having an access
-- token present.
isAccessTokenInStorage : Result err String -> Bool
isAccessTokenInStorage result =
  case result of
    -- If token is empty, no need to call the server.
    Ok token ->
      if String.isEmpty token then False else True

    Err _ ->
      False


-- VIEW

view : Signal.Address Action -> Model -> Html
view address model =
  case model.name of
    Anonymous ->
      div [] [ text "This is wrong - anon user cannot reach this!"]

    LoggedIn name ->
      let
        italicName : Html
        italicName =
          em [] [text name]
      in
        div [class "container"]
          [ div [] [ text "Welcome ", italicName ]
          , div [] [ text "Your repos are:"]
          , viewRepos model.repos
          ]

viewRepos : Maybe (List Repo.Model) -> Html
viewRepos maybeRepos =
  let
    viewRepo repo =
      li [] [ text repo.label ]
  in
    case maybeRepos of
      Just repos ->
        ul  [] (List.map viewRepo repos)

      Nothing ->
        div [] [ text "You have no repos, yet" ]

-- EFFECTS


getJson : String -> AccessToken -> Effects Action
getJson url accessToken =
  let
    encodedUrl = Http.url url [ ("access_token", accessToken) ]
  in
    Http.get decodeData encodedUrl
      |> Task.toResult
      |> Task.map UpdateDataFromServer
      |> Effects.task


decodeData : Json.Decoder (Id, String, Maybe (List Repo.Model))
decodeData =
  let
    -- Cast String to Int.
    number : Json.Decoder Int
    number =
      Json.oneOf [ Json.int, Json.customDecoder Json.string String.toInt ]

    repo =
      Json.object2 Repo.Model
        ("id" := number)
        ("label" := Json.string)

    maybeRepos =
      Json.oneOf
        [ Json.null Nothing
        , Json.map Just <| Json.list repo
        ]
  in
  Json.at ["data", "0"]
    <| Json.object3 (,,)
      ("id" := number)
      ("label" := Json.string)
      -- Repository might be empty.
      ("repository" := maybeRepos)

-- ROUTER

delta2update : Model -> Model -> Maybe HashUpdate
delta2update previous current =
  Just <| RouteHash.set []

location2action : List String -> List Action
location2action list =
  []
