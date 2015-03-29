## WebDriverCSS

Install mocha globally

``npm install -g mocha``

Follow [installation](https://github.com/webdriverio/webdrivercss#install) notes for all the dependencies

```bash
npm install
# Set BrowserStack keys
export BROWSERSTACK_USERNAME=<my user>
export BROWSERSTACK_KEY=<your user>
```

Get your token via the ``/#/my-account`` page.


```json
# ~/.shoov.json

{
  "access_token": "<YOUR-TOKEN>",
  "backend_url": "http://localhost/shoov/www",
  "client_url": "http://localhost:9000",
  "debug": false
}
```


```php
# sites/default/settings.php

$conf['shoov_github_client_id'] = '<CLIENT-ID>';
$conf['shoov_github_client_secret'] = '<CLIENT-SECRET>';
```