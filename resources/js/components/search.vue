<template>
  <div id="search" :class="wrapperClasses" style="z-index: 8000;">
    <form action="/search" method="GET" autocomplete="off">
      <input type="hidden" autocomplete="false" />
      <div class="input-group input-group-lg">
        <input
          type="text"
          name="query"
          @input="debounceSearch"
          @focus="focus = true"
          @blur="focus = false"
          :class="formControlClasses"
          :placeholder="'Søk etter ' + tag"
          :aria-label="tag"
          aria-describedby="button-addon2"
        />
        <div class="input-group-append">
          <button
            :class="buttonClasses"
            type="submit"
            id="button-addon2"
            :disabled="query.length === 0"
            style="min-width: 105px;"
          >
            <div v-if="loading" class="spinner-border spinner-border-sm" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            <span v-else>Søk</span>
          </button>
        </div>
      </div>
      <div v-if="suggestions.length" class="list-group list-group-flush rounded-bottom">
        <a
          v-for="suggestion in suggestions"
          :href="'/forum/posts/' + suggestion.threadid"
          :class="listItemClasses"
        >
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <span class="text-muted">{{ suggestion.postusername }} -</span>
              {{ suggestion.title }}
            </div>
            <div style="min-width: 170px; max-width: 170px;" class="border-left px-2 d-none d-md-block">
              <small class="text-brek">{{ suggestion.forum.title}}</small>
            </div>
          </div>
        </a>
        <li class="list-group-item list-group-item-secondary"><small>Trykk <code>ENTER</code> for å søke i alle innlegg eller velg et forslag fra listen.</small></li>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: ["tag"],
  data: () => ({
    query: "",
    debounce: null,
    suggestions: [],
    focus: false,
    loading: false,
  }),
  watch: {
    query() {
      if (this.query.length === 0) {
        this.query = "";
        this.suggestions = [];
      }
    },
  },
  computed: {
    wrapperClasses() {
      if (this.focus) {
        return "shadow-lg rounded-lg bg-white";
      }

      if (this.focus && this.suggestions.length > 0) {
        return "shadow-lg rounded-lg bg-white search-border";
      }

      return "rounded-lg bg-white";
    },
    formControlClasses() {
      if (this.suggestions.length) {
        return "form-control shadow-none border-0 rounded-left";
      }
      return "form-control shadow-none border-0";
    },
    buttonClasses() {
      if (this.suggestions.length) {
        return "btn btn-secondary rounded-top-right not-rounded-bottom-right";
      }
      return "btn btn-secondary";
    },
    listItemClasses() {
      if (this.loading) {
        return "list-group-item list-group-item-action animate__animated animate__fadeOut";
      }
      return "list-group-item list-group-item-action animate__animated animate__fadeIn";
    },
  },
  methods: {
    debounceSearch(event) {
      clearTimeout(this.debounce);
      this.debounce = setTimeout(() => {
        this.query = event.target.value;
        this.searchAsYouType();
      }, 600);
    },
    searchAsYouType() {
      if (this.query) {
        this.loading = true;
        setTimeout(() => {
          axios
            .post("/api/search_as_you_type", { query: this.query })
            .then((response) => {
              this.loading = false;
              this.suggestions = response.data;
            })
            .catch((error) => {
              this.loading = false;
              console.error(error.message);
            });
        }, 300);
      }
    },
  },
};
</script>
