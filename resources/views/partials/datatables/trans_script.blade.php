<script src="{{ route('translations') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>

<script>
    function trans(path) {
        //Use method of lodash to return
        return _.get(i18n, path);
    }
</script>
