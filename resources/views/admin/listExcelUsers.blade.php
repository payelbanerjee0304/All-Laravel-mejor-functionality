<x-layout>
    <x-slot:color>
        {{'info'}}
    </x-slot:color>
    <x-slot:pageheading>
        {{'List Excel Users'}}
    </x-slot:pageheading>

    <div class="mb-3">
        <div class="card-tools">
            <div class="input-group input-group" style="width: 250px;">
                <input type="text" id="keyword" name="keyword" class="form-control float-right" placeholder="Search">
                <div class="input-group-append">
                  <button type="submit" id="searchSubmit" class="btn btn-default" style="background-color: rgb(76, 137, 172)">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            <a href="{{route('admin.logout')}}" class="btn btn-success" >Logout</a>
            <a href="{{ route('download') }}" class="btn btn-primary">Download</a>
            <div class="filter-section">
                <div id="reportrange">
                    <i class="fas fa-calendar"></i>&nbsp; 
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>
            </div>
            <input type="hidden" id="start_date" name="start_date">
            <input type="hidden" id="end_date" name="end_date">
        </div>
    </div>
    <div class="table-responsiv" id="tableData">
		@include('admin.listExcelUsers_pagination')
    </div>
    
    {{-- for DateRange Filter --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script>
        function fetch_data(page) {
            $.ajax({
                url: "{{route('admin.paginateUsers')}}",
                data:{page:page},
                success: function(data) {
                    $('#tableData').html(data);
                }
            });
        }

        function search_data(keyword, page = 1) {
            $.ajax({
                url: "{{ route('admin.searchUsers') }}",
                data:{keyword: keyword, page: page},
                success: function(data) {
                    $('#tableData').html(data);
                }
            });
        }

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var keyword = $('#keyword').val();
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();

            if(keyword)
            {
                search_data(keyword,page);
            }
            else if(startDate && endDate)
            {
                date_search(startDate,endDate,page);
            }
            else
            {
                fetch_data(page);
            }
        });

        $('#searchSubmit').on('click', function(event) {
            event.preventDefault();
            var keyword = $('#keyword').val();
            search_data(keyword);
        });

        //Start Date Range frontend
        $(function() {
            var start = moment().subtract(29, 'days');
            var end = moment();
            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#start_date').val(start.format('YYYY-MM-DD'));
                $('#end_date').val(end.format('YYYY-MM-DD'));
            }
            $('#reportrange').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                showDropdowns: true,
                alwaysShowCalendars: true,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Cancel',
                    applyLabel: 'Apply'
                }
            }, cb);
            cb(start, end);
        });
        //End Date Range frontend

        
        function date_search(startDate,endDate, page = 1){
        
            $.ajax({
            url: '/admin/dateSearch',
            method: 'GET',
            data: {
                start_date: startDate,
                end_date: endDate,
                page: page,
            },
            success: function(data) {
                // console.error(response);
                $('#tableData').html(data);
            },
            error: function(error) {
                console.error(error);
            }
        });
        
        }

        // Handle apply event
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var startDate = picker.startDate.format('YYYY-MM-DD');
        var endDate = picker.endDate.format('YYYYMMDD');
        
        // Send data to the backend
        date_search(startDate,endDate);
    });
    </script>

</x-layout>