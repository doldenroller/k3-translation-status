<?php
/*
The delete option was mainly taken from https://github.com/Daandelange/k3-translations
*/

Kirby::plugin('doldenroller/k3-translation-status', [
  'sections' => [
    'translationstatus' => require __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'translationStatus.php'
  ],

  // delete route
  'api' => [
    'routes' => function (\Kirby\Cms\App $kirby) {
      return [
        // route to delete content file
        [
          'pattern' => 'plugin-translationstatus/delete',
          'method'  => 'POST',
          'action'  => function () use ($kirby) {

            //$id = str_replace('pages/', '', get('id'));
            //$id = str_replace('+', '/', $id);

            $id = str_replace('+', '/', substr(get('id'), strlen('pages/')));
            $languageCode = get('languageCode');

            // Protect default lang
            if ($kirby->defaultLanguage()->code() === $languageCode) {
              return [
                'code' => 403,
                'text' => t('translations.delete.false', 'The default language content can not be deleted.'),
              ];
            }elseif($page = $kirby->page($id)){
              //$page = $kirby->page($id);
              $filePath = $page->translation($languageCode)->contentFile();
              if(F::exists($filePath)){
                if(F::remove($filePath)){
                  return [
                    'code' => 200,
                    'text' => tt('translations.delete.success', null, ['code' => $languageCode]),
                    'default' => $kirby->defaultLanguage()->code(), // not the prettiest, but I have no clue how to integrate this in th js-part
                  ];
                // file removed
                }else{
                  return [
                    'code' => 500,
                    'text' => tt('translations.delete.error', null, ['code' => $languageCode]),
                  ];
                }
              // file exists
              }else{
                return [
                  'code' => 200,
                  'text' => tt('translations.page.notranslation', null, ['code' => $languageCode]),
                  'default' => $kirby->defaultLanguage()->code(), // not the prettiest, but I have no clue how to integrate this in th js-part
                ];
              }
            // page exists
            }else{
              return [
                'code' => 404,
                'text' => tt('translations.page.notfound', null, ['page' => $id]),
              ];
            }

          }
        ], // end route to delete content file

      ];
    }
  ],
  // delete route

  'translations' => [
    'en' => [
      'translations.delete.confirm' => 'Do you really want to delete the content of this language?',
      'translations.delete.success' => 'The language {{code}} has been successfully deleted.',
      'translations.delete.false' => 'The language {{code}} could not be deleted.',
      'translations.page.notranslation' => 'The language {{code}} was not translated.',
      'translations.page.notfound' => 'The page {{page}} doesn\'t exist.',
      'translations.language.switch' => 'Switch to {{language}}',
      'translations.finished' => 'Finished',
      'translations.unfinished' => 'To be done',
      'translations.all' => 'All Translations done',
    ],
    'de' => [
      'translations.delete.confirm' => 'Möchten Sie den Inhalt dieser Sprache wirklich löschen?',
      'translations.delete.success' => 'Die Sprache {{code}} wurde erfolgreich gelöscht.',
      'translations.delete.false' => 'Die Sprache {{code}} konnte nicht gelöscht werden.',
      'translations.page.notranslation' => 'Die Sprache {{code}} ist nicht übersetzt.',
      'translations.page.notfound' => 'Die Seite {{page}} existiert nicht.',
      'translations.language.switch' => 'Zu {{language}} wechseln',
      'translations.finished' => 'Übersetzt',
      'translations.unfinished' => 'Zu erledigen',
      'translations.all' => 'Alle Übersetzungen angelegt',
    ],

  ],

]);
