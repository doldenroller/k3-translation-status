# Kirby Translation Status

Infosection to display all translations of a page in two lists, seperated wether the language is translated or not. By default a message is displayed if all languages are translated. Additionally the the template of the current page is displayed.
![screenshot translation-status](/screenshot-v2.jpg)

## Install
### Download Zip file

Copy plugin folder into `site/plugins`

### Composer
I have no clue about the composer... but if I get right this should work.
Run `composer require doldenroller/kirby-translation-status`.

## Usage
Find and show translations of your page in the panel or use it as language switch in your blueprints.

### Example
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
    allfinished:
      en: All Translations done
      de: Alle Übersetzungen angelegt
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

```php

  // again either comma-separeted
  'doldenroller.templatestatus.ignore' => 'solutions, default'

  // or as array
  'doldenroller.templatestatus.ignore' => ['solutions', 'default']

```

### Update/Changes in v2.0
First of all Kirby4 support is added. And the active language is underlined.

The config option changed from `templatestatus.ignore` to `doldenroller.templatestatus.ignore`.

And now it is possible to delete a translated content file. This can also be disabled or restricted in the blueprint or in config. The restriction can be made that only user-roles or users can delete the translated content. By now the users are found by their e-mailadresses. Maybe UUID support will be added in the future.

In the blueprint restrictions can be set with the `delete:` property like this:

```yaml
  # complete disabled
  delete: false

  # as comma-sperated list
  delete: admin, chief-editor@website.com

  # or as normal list
  delete:
    - admin
    - chief-editor@website.com

```

Or as global config-option:

```php
  // complete disabled
  'doldenroller.templatestatus.delete' => false

  // again either comma-separeted
  'doldenroller.templatestatus.delete' => 'admin, chief-editor@website.com'

  // or as array
  'doldenroller.templatestatus.delete' => ['admin', 'chief-editor@website.com']

```

In the examples above all users with the`admin` user role and the user with the e-mailadress `chief-editor@website.com` can delete translated content.

## Possible enhencements
These could be difficult but would be nice features:
1. Update hint, that shows when content is updated
2. ~~Delete / reset language, because sometimes its easier to start from scratch~~
3. Add user identification by user-UUID (Not my prioraty, because I mostly work without UUIDs)
4. ~~Refresh/update section when new translation is created~~
5. Check compability with Kirby5

## License

[MIT](https://opensource.org/licenses/MIT)


It is discouraged to use this plugin in any project that promotes racism, sexism, homophobia animal abuse, violence or any other form of hate speech.
