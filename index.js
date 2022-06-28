panel.plugin('doldenroller/k3-translation-status', {
  sections: {
    translationstatus: {
      data: function () {
        return {
          headline: null,
          //text: null,
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
        }
      },

      created: function() {
        this.load().then(response => {
          this.headline = response.headline;
          //this.text = response.text;
          this.translated = response.translated;
        });
      },

      template: `
        <section class="k-translated-section">

          <header v-if="headline" class="k-section-header">
            <k-headline>{{ headline }}</k-headline>
          </header>

          <k-box v-if="translated.template" :theme="'translation-head'">
            <k-text>
              <p><strong>Template:</strong> {{ translated.template }}</p>
            </k-text>
          </k-box>

          <k-box v-if="translated.finished" :theme="'translated'">
            <k-text>
          <strong>{{ translated.finHead }}</strong>
              <ul v-if="translated.unHead">
                <li v-for="item in translated.finished">{{ $t('link-field.change') }}
                  <k-button :text="item.name" @click="change(item.code)">
                </li>
              </ul>
            </k-text>
          </k-box>

          <k-box v-if="translated.unfinished" :theme="'not-translated'">
            <k-text>
          <strong>{{ translated.unHead }}</strong>
              <ul>
                <li v-for="item in translated.unfinished">
                  <k-button :text="item.name" @click="change(item.code)">
                </li>
              </ul>
            </k-text>
          </k-box>

        </section>
      `
    }

  }

});
