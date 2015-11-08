module Dashboard where

import Effects exposing (Effects)
import Html exposing (a, div, h2, h5, hr, li, i, span, text, ul, Html)
import Html.Attributes exposing (id, class, href)
import RouteHash exposing (HashUpdate)


-- MODEL

type Features = LiveMonitor | VisualRegression

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
  let
    header =
      div
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
                , hr [ class "no-margin" ] []
                ]
            ]
        ]

    mainContent =
      div
        [ class "main-content" ]
        [ div
            [ class "wrapper"]
            [ h2 [] [ text "Dashboard" ]
            , dashboardLinks
            ]
        ]

    dashboardLinks =
      div
        [ id "dashboard-links"]
        [ div
            [ class "row text-center"]
            [ dashboardBlock LiveMonitor
            , dashboardBlock VisualRegression
            ]
        ]

    dashboardBlock : Features -> Html
    dashboardBlock feature =
      let
        (url, icon, label) =
           case feature of
              LiveMonitor ->
                ("#", "heartbeat", "Live Monitor")

              VisualRegression ->
                ("#", "history", "Live Monitor")

      in
        div
          [ class "col-sm-6 link" ]
          [ div
              []
              [ a
                  [ href url ]
                  [ div
                      [ class "main-icon" ]
                      [ i
                          [ class <| "fa fa-" ++ icon ]
                          []
                      ]
                  , h5 [] [ text "Live Monitor" ]
                  ]
              ]
          ]

  in
    div
      [ id "homepage" ]
      [ header
      , mainContent
      ]

-- ROUTER

delta2update : Model -> Model -> Maybe HashUpdate
delta2update previous current =
  Just <| RouteHash.set []

location2action : List String -> List Action
location2action list =
  []
