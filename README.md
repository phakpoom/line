# Line bot
Simple Line bot builder.

## How to use 

1. create own bussiness logic by extends `AbstractBuilder`
example:
```php
class SimpleBuilder extends AbstractBuilder {
  public function getTextTemplates(): \Generator
  {
      yield '1+1' => '2';
  }

  public static function getScope(): string
  {
      return 'default';
  }

  public static function getRegisterAcceptText(?string $name): string
  {
      return '';
  }

  public static function getRegisterText(): string
  {
      return '';
  }

  public static function getFallbackMessage()
  {
      return 'fallback';
  }
}

// create bot
$bot = new LineMessagingBot('_TOKEN_', '_SECRET_', new InMemoryLineUserManager());

$bot->handleRequestWithBuilder($_SERVER['HTTP_X_LINE_SIGNATURE'], file_get_contents('php://input'), new SimpleBuilder());
```
