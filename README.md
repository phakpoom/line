# Line bot
Simple Line bot builder.

## How to use 

### Basic bot
Create own bussiness logic by extends `AbstractBuilder`
example:
```php
class SimpleBuilder extends \Bonn\Line\MessageBuilder\AbstractBuilder
{
    /**
     * Input And Reply
     */
    public function getTextTemplates(): \Generator
    {
        yield '1+1' => '2';
        yield '' => '2';
    }

    /**
     * Name of bot
     */
    public static function getScope(): string
    {
        return 'test';
    }

    /**
     * Fallback message
     */
    public static function getFallbackMessage()
    {
        return 'ไม่รู้จักคำของท่าน';
    }

    /**
     * Reply message if input is @see getRegisterText
     */
    public static function getRegisterAcceptText(?string $name): string
    {
        return '';
    }

    /**
     * If you want before use this line bot user have to verify before. return input
     */
    public static function getRegisterText(): string
    {
        return '';
    }
}

// create bot
$bot = new LineMessagingBot('_TOKEN_', '_SECRET_', new InMemoryLineUserManager());

$bot->handleRequestWithBuilder($_SERVER['HTTP_X_LINE_SIGNATURE'], file_get_contents('php://input'), new SimpleBuilder());
```
