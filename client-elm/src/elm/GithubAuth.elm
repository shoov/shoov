module GithubAuth where

import Config exposing (backendUrl)
import Dict exposing (get)
import Effects exposing (Effects, Never)
import Html exposing (..)
import Html.Attributes exposing (..)
import Http
import Json.Decode as JD exposing ((:=))
import Json.Encode as JE exposing (..)
import RouteHash exposing (HashUpdate)
import Task
import UrlParameterParser exposing (ParseResult, parseSearchString)
import WebAPI.Location exposing (location)

import Debug


-- MODEL

type alias AccessToken = String

type Status = Init
  | Error String
  | Fetching
  | Fetched
  | HttpError Http.Error


type alias Model =
  { accessToken: AccessToken
  , status : Status
  , code : Maybe String
  }

initialModel : Model
initialModel =
  { accessToken = ""
  , status = Init
  , code = Nothing
  }


init : (Model, Effects Action)
init =
  ( initialModel
  , Effects.none
  )


-- UPDATE

type Action
  = Activate
  | AuthorizeUser String
  | Deactivate
  | SetError String
  | SetAccessToken AccessToken
  | UpdateAccessTokenFromServer (Result Http.Error AccessToken)

update : Action -> Model -> (Model, Effects Action)
update action model =
  case action of
    Activate ->
      (model, getCodeFromUrl)

    AuthorizeUser code ->
      (model, getJson code)

    Deactivate ->
      (model, Effects.none)

    SetError msg ->
      ( { model | status <- Error msg }
      , Effects.none
      )

    SetAccessToken token ->
      ( { model | accessToken <- token }
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
          -- @todo: Improve.
          , Task.succeed (SetError "HTTP error") |> Effects.task
          )

-- VIEW

view : Signal.Address Action -> Model -> Html
view address model =
  let
    spinner =
      i [ class "fa fa-spinner fa-spin" ] []

    content =
      case model.status of
        Error msg ->
          div []
            [text <| "Error:" ++ msg
            , a [ href "#!/login"] [text "Back to Login"]
            ]
        _ ->
          spinner

  in
    div
      [ id "github-auth-page" ]
      [ div [ class "container"] [ content ]
      ]

-- EFFECTS

getCodeFromUrl : Effects Action
getCodeFromUrl =
  let
    errAction =
      SetError "code property is missing form URL."
    getAction location =
      case (parseSearchString location.search) of
        UrlParameterParser.UrlParams dict ->
          case (Dict.get "code" dict) of
            Just val ->
              AuthorizeUser val
            Nothing ->
              errAction

        UrlParameterParser.Error _ ->
          errAction

    actionTask =
      Task.map getAction WebAPI.Location.location
  in
    Effects.task actionTask


getJson : String -> Effects Action
getJson code =
  Http.post
    decodeAccessToken
    (Config.backendUrl ++ "/auth/github")
    (Http.string <| dataToJson code )
    |> Task.toResult
    |> Task.map UpdateAccessTokenFromServer
    |> Effects.task


dataToJson : String -> String
dataToJson code =
  JE.encode 0
    <| JE.object
        [ ("code", JE.string code) ]

decodeAccessToken : JD.Decoder AccessToken
decodeAccessToken =
  JD.at ["access_token"] <| JD.string

-- ROUTER

delta2update : Model -> Model -> Maybe HashUpdate
delta2update previous current =
  Just <| RouteHash.set []

location2action : List String -> List Action
location2action list =
  []
