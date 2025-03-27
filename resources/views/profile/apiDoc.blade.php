<!DOCTYPE html>
<html>
<head>
    <title>CardEye API Documentation</title>
    <link   rel="shortcut icon" href="{{ asset('image/favicon.ico') }}">
    <!-- needed for adaptive design -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700|Roboto:300,400,700" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body id="redoc-container" style="margin-top: 0px; margin-bottom: 0px; margin-left: 0px; margin-right: 0px; ">
    <script src="https://cdn.jsdelivr.net/npm/redoc@2.0.0-rc.48/bundles/redoc.standalone.js"> </script>
    <script type="text/javascript">
        Redoc.init("{{ url('cardeye.yaml') }}", {
            suppressWarnings: true,
            // requiredPropsFirst: true,
            //sortPropsAlphabetically: true,
            pathInMiddlePanel: false,
            expandDefaultServerVariables: false,
            expandResponses: false,
            maxDisplayedEnumValues: true,
            hideDownloadButton: true,
            hideLoading: false,
            hideSingleRequestSampleTab: false,
            expandSingleSchemaField: false,
            jsonSampleExpandLevel: false,
            hideSchemaTitles: false,
            menuToggle: true,
            scrollYOffset: false,
            untrustedSpec: false,
            theme: {

                typography: {
                    fontFamily: 'Roboto,sans-serif',
                    fontWeight: "900",
                    lineHeight: "1.3rem",
                    headings: {
                        fontFamily: 'Montserrat, sans-serif',
                        //fontWeight: "400"
                        //lineHeight: "1.6em"
                    },
                    // code: {
                    //   //fontFamily: '"Source Code Pro", "Courier", monospace',
                    //   // color: "black",
                    //   //backgroundColor:"rgba(38, 50, 56, 0.05)",
                    //   //backgroundColor:"silver",
                    //   //wrap: ""


                    // },
                    links: {
                      // color: '#ED8F91',
                    }

                },
                rightPanel: {
                    width: '40%',
                    backgroundColor: '#8286fa',
                    textColor: '#f8f9fa',
                },
                menu: {
                    // backgroundColor: '#343a40',
                    // backgroundColor: '#F7F7F9',


                    // textColor: '#ED8F91',
                    // textColor: '#212429',
                },

                codeSample: {
                    backgroundColor: '#28314E'
                    // carde eye color code: ED8F91
                },
                logo: {
                    maxHeight: ({
                        sidebar
                    }) => sidebar.width,
                    maxWidth: ({
                        sidebar
                    }) => sidebar.width,
                    gutter: '35px',
                }


            }
        }, document.getElementById('redoc-container'));
    </script>
    <style>
    .jbXOXf {
    font-size: 0.8em;
    margin-top: 10px;
    padding: 0px 20px;
    text-align: left;
    opacity: 0.7;
    display: none;

}
    </style>
</body>

</html>
