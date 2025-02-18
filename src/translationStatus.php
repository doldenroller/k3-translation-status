<?php
use Kirby\Exception\InvalidArgumentException;
// translations for labels etc.
/*
Variables for language files
  translations.finished
  translations.unfinished
  translations.all
*/

function textValue($text, $fallback = false){
  $panellang = kirby()->user()->language();
    if(is_array($text) && !empty($text[$panellang])){
      $text = $text[$panellang];
    }elseif(is_array($text) && empty($text[$panellang])){
      $text = reset($text); // first value
    }elseif(empty($text) && !empty($fallback)){
      $text = $fallback;
    }
    return $text;
};


$extension = [
  'props' => [
    'headline' => function ($headline = null) {
      if(is_array($headline)){
        $headline = textValue($headline);
      }
      return $headline;
    },
    'label' => function ($label = null) {
      if(is_array($label)){
        $label = textValue($label);
      }
      return $label;
    },

    'extend' => function (bool $extend = null) {
      return $extend;
    },
    'delete' => function ($deleteable = null) {
      return $deleteable;
    }

  ],


  'computed' => [
    'translated' => function () {

      // declare basics
      $kirby = kirby();
      $panellang = $kirby->user()->language();
      $languages = $kirby->languages();
      $page = $this->model();
      $userrole = $kirby->user()->role()->id();
      $useremail = $kirby->user()->email();

      // on default translations can be deleted
      $deleteable = true;

      // check for restrictions to delete a translation (blueprint beats config)
      $delete = $this->delete() ?? $kirby->option('doldenroller.translations.delete');
      if($delete !== null || (is_bool($delete) && $delete === false)){

        // completly disabled
        if(is_bool($delete) && $delete === false){
          $deleteable = false;
        }elseif(!empty($delete)){
          if(!is_array($delete)) $delete = Str::split($delete);
          // if(!in_array($useremail, $delete) || !in_array($userrole, $delete)) - doesnt work somehow... so we wrap it. Not as beautiful, but it works
          if(!(in_array($useremail, $delete) || in_array($userrole, $delete)) ){
            $deleteable = false;
          }
        }

      }


      // create arrays and fill them
      $done = $empty = null;
      foreach($languages as $lang){
        $code = $lang->code();
        $contentFile = $page->translation($code)->contentFile();
        $title = tt('translations.language.switch', null, ['language' => $lang->name()]);

        // check for default language
        $notdefault = true;
        if($lang->isDefault()){
          $notdefault = false;
        }

        if(F::exists($contentFile)){
          $done[] = ['code' => $code, 'name' => $lang->name(), 'deleteable' => $deleteable, 'notdefault' => $notdefault, 'title' => $title];
        }else{
          $empty[] = ['code' => $code, 'name' => $lang->name(), 'title' => $title];
        }
      };

      // create headlines
      $finHead = textValue($this->finished(), t('translations.finished', 'Finished'));
      $unHead = textValue($this->unfinished(), t('translations.unfinished', 'To be done'));

      if( (!is_bool($this->extend()) && $this->extend() !== false) || (is_bool($this->extend()) && $this->extend() === false) ){
        if(!empty($done) && empty($empty)){
          $finHead =  textValue($this->allfinished(), t('translations.all', 'All Finished'));
          $unHead = null;
        }
      }

      //$template = $page->blueprint()->title();
      $template = [
        'name' => $page->blueprint()->title(),
        'icon' => $page->blueprint()->icon()
      ];
      if(is_bool($this->template()) && $this->template() === false){
        $template = null;
      }

      // ignore templates
      if($ignore = $this->ignore() ?? $kirby->option('doldenroller.templatestatus.ignore')){
        if(!is_array($ignore)) $ignore = Str::split($ignore);
        if(in_array($page->intendedTemplate(), $ignore)){
          $done = $empty = $finHead = $unHead = null;
        }

      }

      // return the data
      $status = [];
      $status['template'] = $template;
      $status['finished'] = $done;
      $status['unfinished'] = $empty;
      $status['finHead'] = $finHead;
      $status['unHead'] = $unHead;
//$status['deleteTest'] = $delete;

      return $status;
    }

  ],

];
return $extension;
