panel.plugin('doldenroller/k3-translation-status', {
  sections: {
    translationstatus: {
      data: function () {
        return {
          label: null,
          headline: null,
          //text: null,
          extend: null,
          deletable: null,
          defaultLang: null,
          translated: Array
        }
      },

      methods: {
        change(language) {
          this.$emit("change", language);
          this.$go(window.location, {
            query: {
              language: language
            }
          });
        },

        // delete method
        deleteTranslationOpen(language, code, text) {
          this.dialogLanguage = code;
          let dialogText = text + ' (' + language + ')';
          this.$panel.dialog.open({
            component: 'k-remove-dialog',
            props: {
              text: dialogText,
            },
            on: {
              submit: () => {
                this.$api.post('plugin-translationstatus/delete', {id: window.panel.view.path, languageCode: code})
                  .then(response => {
                    if (response.code === 200) {
                      window.panel.notification.success(response.text);
                      //console.log(window.panel.view);
                      this.change(response.default);
                      // not the finest solution, but at least no error. there should be a way to updated only the section but i don't know how...
                      window.setTimeout(() => {
                        location.reload();
                      }, 300);

                    }
                    else {
                      window.panel.notification.error(response.text);
                    }
                  })
                .catch(error => {
                  console.log(error);
                  window.panel.notification.error(error);
                });
              } // submit
            }
          }) // dialog
        },
      // delete method

      },

      created: function() {
        this.load().then(response => {
          this.label = response.label;
          this.headline = response.headline;
          //this.text = response.text;
          this.translated = response.translated;

          window.panel.events.on('model.update', () => {
            if(!window.panel.language.isDefault){
              if(this.translated.unfinished !== null && this.translated.unfinished.findIndex((x) => x.code === window.panel.language.code) !== -1 ){
                console.log('SAVE', this.translated.unfinished);
                window.setTimeout(() => {
                  location.reload();
                }, 300);
              }
            }
          });



        });
      },

      template: `
        <section class="k-section k-translated-section">

          <header v-if="label" class="k-section-header">
            <k-headline>{{ label }}</k-headline>
          </header>

          <header v-else-if="headline" class="k-section-header">
            <k-headline>{{ headline }}</k-headline>
          </header>

          <k-box v-if="translated.template" :theme="'translation-head'">
            <k-text>
              <p><strong>Template:</strong> <k-icon :type="translated.template.icon" color="gray-800" class="k-info-icon"></k-icon> {{ translated.template.name }}</p>
            </k-text>
          </k-box>

          <k-box v-if="translated.finished" :theme="'translated'">
            <k-text>
          <strong>{{ translated.finHead }}</strong>
              <ul v-if="translated.unHead">
                <li v-for="item in translated.finished" class="k-translation-buttons">
                  <k-button-group>
                    <k-button v-if="item.code == window.panel.language.code" :text="item.name" @click="change(item.code)" current="true" variant="dimmed" />
                    <k-button v-else :text="item.name" @click="change(item.code)" :title="item.title" />
                    <k-button v-if="item.deleteable && item.notdefault" icon="trash" @click.stop="deleteTranslationOpen(item.name, item.code, $t('translations.delete.confirm'))" variant="filled" theme="negative-icon" size="xs" class="k-translations-button" />
                  </k-button-group>
                </li>
              </ul>
            </k-text>
          </k-box>

          <k-box v-if="translated.unfinished" :theme="'not-translated'">
            <k-text>
          <strong>{{ translated.unHead }}</strong>
              <ul>
                <li v-for="item in translated.unfinished" class="k-translation-buttons">
                    <k-button v-if="item.code == window.panel.language.code" :text="item.name" @click="change(item.code)" current="true" variant="dimmed" />
                    <k-button v-else :text="item.name" @click="change(item.code)" :title="item.title" />
                </li>
              </ul>
            </k-text>
          </k-box>


        </section>
      `
    }

  }

});
