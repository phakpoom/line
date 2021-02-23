```php
require dirname(__DIR__) . '/vendor/autoload.php';

class VerifyBuilder extends \Bonn\Line\MessageBuilder\AbstractBuilder
{
    public function getTextTemplates(): \Generator
    {
        [$active, $info] = $this->getUserActiveState();

        if ('cat_or_dog' === $active) {
            yield '/(cat|dog)/' => function (array $m) {
                $this->setUserActiveState(null);

                return "I love your {$m[1]}.";
            };

            yield self::ALL_CHAR_REGEX => function (array $m) {
                $this->setUserActiveState(null);

                return "Something wrong! Please type cat or dog";
            };
        }

        yield 'pet' => function () {
            $this->setUserActiveState('cat_or_dog');

            return 'Cat or Dog';
        };
    }

    public static function getScope(): string
    {
        return 'active';
    }

    public static function getFallbackMessage()
    {
        return \Bonn\Line\MessageBuilder\Sticker::randomStickerMessage();
    }
}

// create bot
$bot = new \Bonn\Line\LineMessagingBot('_TOKEN_', '_SECRET_', new \Bonn\Line\InMemoryLineUserManager());

$bot->handleRequestWithBuilder($_SERVER['HTTP_X_LINE_SIGNATURE'], file_get_contents('php://input'), new VerifyBuilder());
```

input "pet" -> "Cat or Dog"

input "cat" -> "I love your cat."


input "pet" -> "Cat or Dog"

input "dog" -> "I love your dog."


input "pet" -> "Cat or Dog"

input "bird" -> "Something wrong! Please type cat or dog"
