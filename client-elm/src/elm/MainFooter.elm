module MainFooter (html) where

import Html exposing (Html, footer, div, span, a, i, text)
import Html.Attributes exposing (class, id, href, target)

  -- Footer html output
html : Html
html =
  let
    -- Item helper.
    item : (String, String) -> Html
    item (className, textValue) =
      span [] [ i [ class <| className ] [] , text <| textValue ]


    -- Link helper.
    link : (String, String, String) -> Html
    link (className, url, textValue) =
      a [ target "_blank", class <| className, href  <| url ] [ text <| textValue ]


    -- Gizra website link.
    gizraLogo =
      link ( "email","mailto:info@gizra.com", "info@gizra.com" )


    -- Gizra email link.
    email =
      link ( "gizra-logo", "http://www.gizra.com", "gizra" )


  in
    footer [ id "footer" ]
           [ div [ class "container company-details" ]
                 [ span [] [ email ]
                 , item ( "fa fa-phone", "Tel: +972-3-3731222 | Fax: +972-3-5617771" )
                 , span [] [ i [ class "fa fa-envelope-o"] [], gizraLogo ]
                 , item ( "fa fa-copyright", "2015" )
                 ]
          ]
