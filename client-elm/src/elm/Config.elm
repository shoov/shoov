module Config where

import Time exposing (Time)

backendUrl : String
backendUrl = "http://localhost/shoov/www"

cacheTtl : Time.Time
cacheTtl = (5 * Time.second)

githubClientId : String
githubClientId = "e9fe6d8d6185db84d5a7"
