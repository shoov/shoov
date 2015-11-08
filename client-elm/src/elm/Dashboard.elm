module Dashboard where

import Effects exposing (Effects)
import Html exposing (a, div, li, i, span, text, ul, Html)
import Html.Attributes exposing (id, class)
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
  div
    [ id "homepage" ]
    [ div
        [ class "row"]
        [ div
            [ class "col-sm-12" ]
            [ div
                [ class "page-bar" ]
                [ ul
                    [ class "page-breadcrumb" ]
                    [ li
                        [ class "active"]
                        [ i [ class "fa fa-desktop"] []
                        , span [] [ text "Dashboard" ]
                        ]
                    ]

                ]
            ]
        ]

    ]

-- ROUTER

delta2update : Model -> Model -> Maybe HashUpdate
delta2update previous current =
  Just <| RouteHash.set []

location2action : List String -> List Action
location2action list =
  []
