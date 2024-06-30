<div class="modal fade tableFilterModal" id="advanced_filtersModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class='bx bx-filter'></i> Advanced Filters</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <advanced-filters :operators="{{ json_encode($advancedFilters['operators']) }}"
                    :fields="{{ json_encode($advancedFilters['fields']) }}"></advanced-filters>
            </div>
        </div>
    </div>
</div>

@section('scripts')
@parent
<script>
    $(function () {
        $('#advanced_filtersModalBtn').on('click', function() {
            $('#advanced_filtersModal').modal('show');
        });
    });

    function getInputValue(name) {
        var inputValues = [];
        var inputs = $('[id^="advanced_filter_' + name + '_"]');

        inputs.each(function(index, input) {
            inputValues.push($(input).val());
        });

        return inputValues;
    }
</script>
@endsection
