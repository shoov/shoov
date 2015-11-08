module Dashboard where

import Effects exposing (Effects)
import Html exposing (a, div, span, text, Html)
import Html.Attributes exposing (id)
import RouteHash exposing (HashUpdate)


-- MODEL

type alias Model =
  {}

initialModel : Model
initialModel =
  {}

init : (Model, Effects Action)
init =
  ( initialModel
  , Effects.none
  )


-- UPDATE

type Action
  = Activate
  | Deactivate

update :Action -> Model -> (Model, Effects Action)
update action model =
  case action of
    Activate ->
      (model, Effects.none)

    Deactivate ->
      (model, Effects.none)


-- VIEW

view : Signal.Address Action -> Model -> Html
view address model =
  div [id "dashboard-page"] [ text "Dashboard" ]

-- ROUTER

delta2update : Model -> Model -> Maybe HashUpdate
delta2update previous current =
  Just <| RouteHash.set []

location2action : List String -> List Action
location2action list =
  []
