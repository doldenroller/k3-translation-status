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
  ],

  'computed' => [
    'translated' => function () {

      // declare basics
      $kirby = kirby();
      $panellang = $kirby->user()->language();
      $languages = $kirby->languages();
      $page = $this->model();

      // create arrays and fill them
      $done = $empty = null;
      foreach($languages as $lang){
        $code = $lang->code();
        $contentFile = $page->contentFile($code);

        if(F::exists($contentFile)){
          $done[] = ['code' => $code, 'name' => $lang->name()];
        }else{
          $empty[] = ['code' => $code, 'name' => $lang->name()];
        }
      };

      // create headlines
      $finHead = textValue($this->finished(), t('translations.finished', 'Finished'));
      $unHead = textValue($this->unfinished(), t('translations.unfinished', 'To be done'));

      if(!is_bool($this->extend()) && $this->extend() !== false){
        if(!empty($done) && empty($empty)){
          $finHead =  textValue($this->allfinished(), t('translations.all', 'All Finished'));
          $unHead = null;
        }
      }

      $template = $page->blueprint()->title();
      if(is_bool($this->template()) && $this->template() === false){
        $template = null;
      }

      // ignore templates
      if($ignore = $this->ignore() ?? $kirby->option('templatestatus.ignore')){
        if(!is_array($ignore)) $ignore = explode (',', $ignore);
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

      return $status;
    }

  ]

];
return $extension;
