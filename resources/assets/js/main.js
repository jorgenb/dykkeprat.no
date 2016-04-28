Vue.component('results', {
    template: '#results-template',
});

new Vue({
    el: 'body',
    data: function () {

        return {
            query: '',
            results: []
        }
    },
    methods: {
        search: function () {

            var value = this.query;

            if (value.length < 3) {
                this.$set('results', []);
                return;
            }

            // GET request
            this.$http.get('/api/search?q=' + this.query).then(function (response) {

                // get status
                response.status;

                // get all headers
                response.headers();

                // get 'expires' header
                response.headers('expires');

                //console.log(response.data);

                // set data on vm
                this.$set('results', response.data);

                //$.each(response.data, function (key, values) {
                //    console.log(values.inner_hits);
                //})

            }, function (response) {

                // error callback
            });
        },
        clear: function () {
            if (this.query.length < 2) this.$set('results', []);
        }
    }
});