```php
require dirname(__DIR__) . '/vendor/autoload.php';

class SimpleBuilder extends \Bonn\Line\MessageBuilder\AbstractBuilder
{
    /**
     * Input And Reply
     */
    public function getTextTemplates(): \Generator
    {
        yield '1+1' => '2';
        yield '/(\d+)\+(\d+)/' => function (array $m) {
            return (string) ($m[1] + $m[2]);
        };
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
        return "Fallback";
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
input "1+1" -> "2"

input "10+10" -> "20"

input "xx" -> "Fallback"

Next [Verify bot](https://github.com/phakpoom/line/blob/master/doc/2-Verify-Bot.md)
