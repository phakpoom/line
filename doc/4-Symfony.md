## Symfony User ?
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
