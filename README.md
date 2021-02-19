# Line bot
Simple Line bot builder.

## Installation

```composer require phakpoom/line```

## How to use
0. [Preparing](https://github.com/phakpoom/line/doc/0-Preparing.md)
1. [Simple bot](https://github.com/phakpoom/line/doc/1-Simple-Bot.md)
2. [Verify bot](https://github.com/phakpoom/line/doc/1-Verify-Bot.md)
3. [Active bot](https://github.com/phakpoom/line/doc/1-Active-Bot.md)


## Use Symfony ?
1. add bundles.php
```php
    Bonn\Bridge\Symfony\Bundle\LineBotBundle\BonnLineBotBundle::class => ['all' => true],
```
2. add route
```yaml
line_hook:
    path: /line-hook
    defaults:
        _controller: bonn_line.controller::hookAction
        builderClass: App\Line\YourBuilder
        token: "_TOKEN_"
        secret: "_SECRET_"
```

Enjoy..
