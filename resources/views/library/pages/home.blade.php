@extends('library.layouts.backend')

@section('css_before')
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/plugins/fullcalendar/main.min.css') }}">
@endsection

@section('content')
    <div class="content">

        <!-- DASH CARDS -->
        <div class="row row-deck">
            <div class="col-sm-6 col-xl-3">
                <!-- Pending Orders -->
                <div class="block block-rounded d-flex flex-column">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{ $totalBorrowed ?? 0 }}</dt>
                            <dd class="text-muted mb-0">Borrowed</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            {{-- <i class="fa fa-shopping-cart font-size-h3 text-primary"></i> --}}
                            <img src="{{ asset('assets/lms/book.png') }}" alt="book img" style="height: 50px; width: 50px;">
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                        <a class="font-w500 d-flex align-items-center" href="/library/admin/circulation/borrowed?action=2">
                            More Info
                            <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Pending Orders -->
            </div>
            <div class="col-sm-6 col-xl-3">
                <!-- New Customers -->
                <div class="block block-rounded d-flex flex-column">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{ $totalReturned ?? 0 }}</dt>
                            <dd class="text-muted mb-0">Returned</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            {{-- <i class="fa fa-users font-size-h3 text-primary"></i> --}}
                            <img src="{{ asset('assets/lms/return.png') }}" alt="book img"
                                style="height: 50px; width: 50px;">
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                        <a class="font-w500 d-flex align-items-center" href="/library/admin/circulation/returned?action=3">
                            More Info
                            <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END New Customers -->
            </div>
            <div class="col-sm-6 col-xl-3">
                <!-- Messages -->
                <div class="block block-rounded d-flex flex-column">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{ $totalIssued ?? 0 }}</dt>
                            <dd class="text-muted mb-0">Issued</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            {{-- <i class="fa fa-inbox font-size-h3 text-primary"></i> --}}
                            <img src="{{ asset('assets/lms/issued.png') }}" alt="book img"
                                style="height: 50px; width: 50px;">
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                        <a class="font-w500 d-flex align-items-center" href="/library/admin/circulation/issued?action=1">
                            More Info
                            <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Messages -->
            </div>
            <div class="col-sm-6 col-xl-3">
                <!-- Conversion Rate -->
                <div class="block block-rounded d-flex flex-column">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="font-size-h2 font-w700">{{ $totalLost ?? 0 }}</dt>
                            <dd class="text-muted mb-0">Lost</dd>
                        </dl>
                        <div class="item item-rounded bg-body">
                            {{-- <i class="fa fa-chart-line font-size-h3 text-primary"></i> --}}
                            <img src="{{ asset('assets/lms/lost.png') }}" alt="book img"
                                style="height: 50px; width: 50px;">
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light font-size-sm">
                        <a class="font-w500 d-flex align-items-center" href="/library/admin/circulation/lost?action=4">
                            More Info
                            <i class="fa fa-arrow-alt-circle-right ml-1 opacity-25 font-size-base"></i>
                        </a>
                    </div>
                </div>
                <!-- END Conversion Rate-->
            </div>
        </div>

        <!-- GRAPHS -->
        <div class="row">
            <div class="col-md-8">
                <div class="block block-rounded flex-grow-1 d-flex flex-column">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Monthly Transactions Report</h3>
                        <div class="block-options">
                            <div class="dropdown">
                                <button type="button" class="btn btn-alt-primary dropdown-toggle"
                                    id="dropdown-align-primary" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">
                                    This Month
                                </button>
                                <div class="dropdown-menu dropdown-menu-right font-size-sm"
                                    aria-labelledby="dropdown-align-primary">
                                    <a class="dropdown-item" href="#" onclick="changeMonth('January')">January</a>
                                    <a class="dropdown-item" href="#" onclick="changeMonth('February')">February</a>
                                    <a class="dropdown-item" href="#" onclick="changeMonth('March')">March</a>
                                    <a class="dropdown-item" href="#" onclick="changeMonth('April')">April</a>
                                    <a class="dropdown-item" href="#" onclick="changeMonth('May')">May</a>
                                    <a class="dropdown-item" href="#" onclick="changeMonth('June')">June</a>
                                    <a class="dropdown-item" href="#" onclick="changeMonth('July')">July</a>
                                    <a class="dropdown-item" href="#" onclick="changeMonth('August')">August</a>
                                    <a class="dropdown-item" href="#"
                                        onclick="changeMonth('September')">September</a>
                                    <a class="dropdown-item" href="#" onclick="changeMonth('October')">October</a>
                                    <a class="dropdown-item" href="#"
                                        onclick="changeMonth('November')">November</a>
                                    <a class="dropdown-item" href="#"
                                        onclick="changeMonth('December')">December</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full flex-grow-1 d-flex align-items-center">
                        <!-- Earnings Chart Container -->
                        <!-- Chart.js Chart is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _es6/pages/be_pages_dashboard.js -->
                        <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                        <canvas class="js-chartjs-earnings"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <!-- Calendar -->
                <div class="block block-rounded">
                    <div class="block-content">
                        <div class="row items-push">
                            <div class="col-md-12">
                                <!-- Calendar Container -->
                                <div id="js-calendar"></div>
                            </div>
                            <ul id="js-events" class="list list-events">
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- END Calendar -->
            </div>
        </div>

        <div class="row">
            <!-- OVERDUE TABLE -->
            <div class="col-12">
                <div class="block block-rounded">
                    <div class="block-header bg-danger">
                        <h3 class="block-title text-light"><i class="far fa-calendar-times mr-1"></i> Overdues</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table-vcenter" style="width: 100%;"
                                id="tb_overdue">
                                <thead>
                                    <tr>
                                        <th>Book Name</th>
                                        <th>Borrower's Name</th>
                                        <th>Date Borrowed</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js_after')
    <script src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('js/plugins/easy-pie-chart/jquery.easypiechart.min.js') }}"></script>
    <script src="{{ asset('js/plugins/jquery-sparkline/jquery.sparkline.min.js') }}"></script>
    <script src="{{ asset('js/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/pages/tables_datatables.js') }}"></script>
    <script src="{{ asset('js/plugins/fullcalendar/main.min.js') }}"></script>
    <script>
        var jsonData = {!! json_encode($jsonData) !!};
        var transactions = {!! json_encode($transactions) !!};

        $(document).ready(function() {
            load_overdue_datatable(jsonData);
            // load_graphs(transactions);
            changeMonth('January');
            console.log(transactions)
        });

        function changeMonth(month) {
            // Set the button text to the selected month
            $('#dropdown-align-primary').text(month);
            $.ajax({
                type: 'GET',
                url: '{{ route('load_graphs') }}',
                data: {
                    month: month
                },
                success: function(data) {
                    // Update the chart with the new data
                    console.log(data)
                    load_graphs(data);
                },
                error: function(error) {
                    console.error('Error fetching data:', error);
                }
            });
        }

        function load_overdue_datatable(data) {
            var table = $('#tb_overdue').DataTable({
                data: data,
                stateSave: true,
                destroy: true,
                lengthChange: true,
                responsive: true,
                columns: [{
                        data: "book_title",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.book_title}</span>`
                        }
                    },
                    {
                        data: 'circulation_name',
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<a class="font-size-sm font-w600" >${row.circulation_name}</a>`
                        }
                    },
                    {
                        data: 'circulation_due_date',
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm text-danger">${row.circulation_due_date}</span>`
                        }
                    },
                    {
                        data: "contactnum",
                        className: 'text-center',
                        render: function(type, data, row) {
                            return `<span class="font-size-sm" >${row.contactnum ?? 'Not Specified'}</span>`
                        }
                    },
                ],
            });
        }
    </script>

    <script>
        function load_graphs(graphdata) {
            ! function(e) {
                var r = {};

                function t(o) {
                    if (r[o]) return r[o].exports;
                    var n = r[o] = {
                        i: o,
                        l: !1,
                        exports: {}
                    };
                    return e[o].call(n.exports, n, n.exports, t), n.l = !0, n.exports
                }
                t.m = e, t.c = r, t.d = function(e, r, o) {
                    t.o(e, r) || Object.defineProperty(e, r, {
                        enumerable: !0,
                        get: o
                    })
                }, t.r = function(e) {
                    "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
                        value: "Module"
                    }), Object.defineProperty(e, "__esModule", {
                        value: !0
                    })
                }, t.t = function(e, r) {
                    if (1 & r && (e = t(e)), 8 & r) return e;
                    if (4 & r && "object" == typeof e && e && e.__esModule) return e;
                    var o = Object.create(null);
                    if (t.r(o), Object.defineProperty(o, "default", {
                            enumerable: !0,
                            value: e
                        }), 2 & r && "string" != typeof e)
                        for (var n in e) t.d(o, n, function(r) {
                            return e[r]
                        }.bind(null, n));
                    return o
                }, t.n = function(e) {
                    var r = e && e.__esModule ? function() {
                        return e.default
                    } : function() {
                        return e
                    };
                    return t.d(r, "a", r), r
                }, t.o = function(e, r) {
                    return Object.prototype.hasOwnProperty.call(e, r)
                }, t.p = "", t(t.s = 0)
            }([function(e, r, t) {
                e.exports = t(1)
            }, function(e, r) {
                function t(e, r) {
                    for (var t = 0; t < r.length; t++) {
                        var o = r[t];
                        o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0),
                            Object.defineProperty(e, o.key, o)
                    }
                }
                var o = function() {
                    function e() {
                        ! function(e, r) {
                            if (!(e instanceof r)) throw new TypeError("Cannot call a class as a function")
                        }(this, e)
                    }
                    var r, o, n;
                    return r = e, n = [{
                        key: "initCharts",
                        value: function() {
                            Chart.defaults.global.defaultFontColor = "#495057", Chart.defaults.scale
                                .gridLines.color = "transparent", Chart.defaults.scale.gridLines
                                .zeroLineColor = "transparent", Chart.defaults.scale.ticks
                                .beginAtZero = !0, Chart.defaults.global.elements.line.borderWidth =
                                0,
                                Chart.defaults.global.elements.point.radius = 0, Chart.defaults
                                .global
                                .elements.point.hoverRadius = 0, Chart.defaults.global.tooltips
                                .cornerRadius = 3, Chart.defaults.global.legend.labels.boxWidth =
                                12;
                            var e, r = jQuery(".js-chartjs-earnings");
                            e = {
                                labels: graphdata.labels,
                                datasets: [{
                                        label: graphdata.datasets[0].label,
                                        fill: !0,
                                        backgroundColor: "rgba(81, 121, 214, .75)",
                                        borderColor: "transparent",
                                        pointBackgroundColor: "rgba(81, 121, 214, 1)",
                                        pointBorderColor: "#fff",
                                        pointHoverBackgroundColor: "#fff",
                                        pointHoverBorderColor: "rgba(81, 121, 214, 1)",
                                        data: graphdata.datasets[0].data
                                    },
                                    {
                                        label: graphdata.datasets[1].label,
                                        fill: !0,
                                        backgroundColor: "rgba(81, 121, 214, .25)",
                                        borderColor: "transparent",
                                        pointBackgroundColor: "rgba(81, 121, 214, 1)",
                                        pointBorderColor: "#fff",
                                        pointHoverBackgroundColor: "#fff",
                                        pointHoverBorderColor: "rgba(81, 121, 214, 1)",
                                        data: graphdata.datasets[1].data
                                    }
                                ]
                            }, r.length && new Chart(r, {
                                type: "bar",
                                data: e,
                                options: {
                                    tooltips: {
                                        intersect: !1,
                                        callbacks: {
                                            label: function(e, r) {
                                                return r.datasets[e.datasetIndex]
                                                    .label +
                                                    ": " + e.yLabel
                                            }
                                        }
                                    }
                                }
                            })
                        }
                    }, {
                        key: "init",
                        value: function() {
                            this.initCharts()
                        }
                    }], (o = null) && t(r.prototype, o), n && t(r, n), e
                }();
                jQuery((function() {
                    o.init()
                }))
            }]);

        }

        jQuery(function() {
            One.helpers(['easy-pie-chart', 'sparkline']);
        });
    </script>

    <script>
        !(function(e) {
            var t = {};

            function n(r) {
                if (t[r]) return t[r].exports;
                var a = (t[r] = {
                    i: r,
                    l: !1,
                    exports: {}
                });
                return e[r].call(a.exports, a, a.exports, n), (a.l = !0), a.exports;
            }
            (n.m = e),
            (n.c = t),
            (n.d = function(e, t, r) {
                n.o(e, t) || Object.defineProperty(e, t, {
                    enumerable: !0,
                    get: r
                });
            }),
            (n.r = function(e) {
                "undefined" != typeof Symbol &&
                    Symbol.toStringTag &&
                    Object.defineProperty(e, Symbol.toStringTag, {
                        value: "Module"
                    }),
                    Object.defineProperty(e, "__esModule", {
                        value: !0
                    });
            }),
            (n.t = function(e, t) {
                if ((1 & t && (e = n(e)), 8 & t)) return e;
                if (4 & t && "object" == typeof e && e && e.__esModule) return e;
                var r = Object.create(null);
                if (
                    (n.r(r),
                        Object.defineProperty(r, "default", {
                            enumerable: !0,
                            value: e
                        }),
                        2 & t && "string" != typeof e)
                )
                    for (var a in e)
                        n.d(
                            r,
                            a,
                            function(t) {
                                return e[t];
                            }.bind(null, a)
                        );
                return r;
            }),
            (n.n = function(e) {
                var t =
                    e && e.__esModule ?
                    function() {
                        return e.default;
                    } :
                    function() {
                        return e;
                    };
                return n.d(t, "a", t), t;
            }),
            (n.o = function(e, t) {
                return Object.prototype.hasOwnProperty.call(e, t);
            }),
            (n.p = ""),
            n((n.s = 0));
        })([
            function(e, t, n) {
                e.exports = n(1);
            },
            function(e, t) {
                function n(e, t) {
                    for (var n = 0; n < t.length; n++) {
                        var r = t[n];
                        (r.enumerable = r.enumerable || !1),
                        (r.configurable = !0),
                        "value" in r && (r.writable = !0),
                            Object.defineProperty(e, r.key, r);
                    }
                }
                var r = (function() {
                    function e() {
                        !(function(e, t) {
                            if (!(e instanceof t))
                                throw new TypeError("Cannot call a class as a function");
                        })(this, e);
                    }
                    var t, r, a;
                    return (
                        (t = e),
                        (a = [{
                                key: "addEvent",
                                value: function() {
                                    var e = jQuery(".js-add-event"),
                                        t = "";
                                    jQuery(".js-form-add-event").on("submit", function(n) {
                                        return (
                                            (t = e.prop("value")) &&
                                            (jQuery("#js-events").prepend(
                                                    '<li><div class="js-event p-2 text-white font-size-sm font-w500 bg-info">' +
                                                    jQuery("<div />").text(t).html() +
                                                    "</div></li>"
                                                ),
                                                e.prop("value", "")),
                                            !1
                                        );
                                    });
                                },
                            },
                            {
                                key: "initEvents",
                                value: function() {
                                    new FullCalendar.Draggable(document.getElementById(
                                        "js-events"), {
                                        itemSelector: ".js-event",
                                        eventData: function(e) {
                                            return {
                                                title: e.innerText,
                                                backgroundColor: getComputedStyle(e)
                                                    .backgroundColor,
                                                borderColor: getComputedStyle(e)
                                                    .backgroundColor,
                                            };
                                        },
                                    });
                                },
                            },
                            {
                                key: "initCalendar",
                                value: function() {
                                    var e = new Date(),
                                        t = e.getDate(),
                                        n = e.getMonth(),
                                        r = e.getFullYear();
                                    new FullCalendar.Calendar(
                                        document.getElementById("js-calendar"), {
                                            themeSystem: "bootstrap",
                                            firstDay: 1,
                                            editable: !0,
                                            droppable: !0,
                                            headerToolbar: {
                                                left: "title",
                                                right: "prev,next today",
                                            },
                                            drop: function(e) {
                                                e.draggedEl.parentNode.remove();
                                            },
                                            events: [],
                                        }
                                    ).render();
                                },
                            },
                            {
                                key: "init",
                                value: function() {
                                    this.addEvent(), this.initEvents(), this.initCalendar();
                                },
                            },
                        ]),
                        (r = null) && n(t.prototype, r),
                        a && n(t, a),
                        e
                    );
                })();
                jQuery(function() {
                    r.init();
                });
            },
        ]);
    </script>
@endsection
