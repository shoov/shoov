import App exposing (init, update, view)
import CustomStartApp as StartApp
import Html exposing (Html)
import Effects exposing (Never)
import RouteHash
import Task exposing (Task)


app : StartApp.App App.Model App.Action
app =
  StartApp.start
    { init = init
    , update = update
    , view = view
    , inputs = []
    }


main : Signal Html.Html
main =
  app.html


port tasks : Signal (Task.Task Never ())
port tasks =
  app.tasks

port routeTasks : Signal (Task () ())
port routeTasks =
    RouteHash.start
        { prefix = RouteHash.defaultPrefix
        , address = app.address
        , models = app.model
        , delta2update = App.delta2update
        , location2action = App.location2action
        }
