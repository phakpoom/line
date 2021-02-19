# Line bot
Simple Line bot builder.

## How to use 

### Basic bot
Create own bussiness logic by extends `AbstractBuilder`
example:
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


### Verify First bot

#### You have to implement @see \Bonn\Line\LineUserManagerInterface connect with your database.
```php
require dirname(__DIR__) . '/vendor/autoload.php';

class VerifyBuilder extends \Bonn\Line\MessageBuilder\AbstractBuilder
{
    public function getTextTemplates(): \Generator
    {
        yield 'foo' => 'bar';
        yield from $this->bar(); // For clean code if you have a lot of yield
    }

    public static function getScope(): string
    {
        return 'verify';
    }

    public static function getFallbackMessage()
    {
        return \Bonn\Line\MessageBuilder\Sticker::randomStickerMessage();
    }

    public static function getRegisterAcceptText(?string $name): string
    {
        return 'Welcome to fight club, wait admin approve.';
    }

    public static function getRegisterText(): string
    {
        return 'join';
    }
    
    private function bar(): \Generator
    {
        yield 'bar' => 'baz';
    }
}

// create bot
$bot = new \Bonn\Line\LineMessagingBot('mCRhyrNYU7pz89imuWkMUJVe5Ug2/ufb9AK2gxiYHwKa/UqFPs+2Ctgr4yvSwmDWviM9VLz4gZq7daK4uQ9JtF6Y4rWQT67yW8Q40tJ/Sf7pNN7wtY7xlS6rYMNSWIrlAD8xK295zKBl7txynm+wuAdB04t89/1O/w1cDnyilFU=', '035af192ffd8d6a52cfff00a89f65652', new \Bonn\Line\InMemoryLineUserManager());

$bot->handleRequestWithBuilder($_SERVER['HTTP_X_LINE_SIGNATURE'], file_get_contents('php://input'), new VerifyBuilder());
```

input "foo" -> ""

input "join" -> "Welcome to fight club, wait admin approve."

#### set LineUser enable
```php
/** @var array|\Bonn\Bundle\LineBotBundle\Model\LineUserInterface[] $allUsers */
$allUsers = $lineManager->findEnabledUserFromScope('verify');

foreach ($allUsers as $user) {
    $user->setEnabled(true);

    $lineManager->save($user);
}

```
#### After enable

input "foo" -> "bar"
input "bar" -> "baz"
input "xx" -> Random sticker
