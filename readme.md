Infosection to display all translations of a page in two lists, seperated wether the language is translated or not. By default a message is displayed if all languages are translated. Additionally the the template of the current page is displayed.
![screenshot translation-status](/screenshot.jpg)

# Install
## Download Zip file

Copy plugin folder into `site/plugins`

## Composer
I have no clue about the composer... but if I get right this should work.
Run `composer require doldenroller/k3-translation-status`.

# Usage
Find and show translations of your page or use it as language switch in your blueprints.

## Example
Basic setup:

```yaml
sections:
  mysection:
    headline: Page Translations
    type: translationstatus
    finished: Translated Languages
    unfinished: Translations to be done
    allfinished: All Translations done
```


With translations in Blueprint:

```yaml
sections:
  mysection:
    headline: Page Translations
    type: translationstatus
    finished:
      en: Translated Languages
      de: Übersetzte Sprachen
    unfinished:
      en: Translations to be done
      de: Noch zu erledigen
    extend: true
```

Translations can also be setup in your languagefiles of your setup:

```yaml
  'translations.finished' => '',
  'translations.unfinished' => '',
  'translations.all' => '',

```

More Options:
With the `extend: true` property, the finished list is still showing when all translations are done, so you can still use it as a language switch.
The `template: false` property hides the intended Template information.

And finally you can exclude pages by template. Either with the `ignore:` property like this:

```yaml
  # as comma-sperated list
  ignore: news, jobs

  # or as normal list
  ignore:
    - news
    - jobs

```

This can also be setup as global option in your config

```yaml

  # again either comma-separeted
  'templatestatus.ignore' => 'solutions, default'

  # or as array
  'templatestatus.ignore' => ['solutions', 'default']

```

## Possible enhencements
These could be difficult but would be niche features:
1. Update hint, that shows when content is updated
2. Delete / reset language, because sometimes its easier to start from scratch

## License

[MIT](https://opensource.org/licenses/MIT)


It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia animal abuse, violence or any other form of hate speech.
