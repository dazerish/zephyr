<div class="user-container">
<h1 class="page-title"><b>Dashboard</b></h1>

    <script>
                // passing the dashboard data from php to javascript for manipulation and display
                var pie_data = <?php echo json_encode($dashboard_data[0]); ?>;
                <?php $device_in_data = json_encode($dashboard_data[1][0]->device_count); ?>;
                <?php $device_out_data = json_encode($dashboard_data[2][0]->device_count); ?>;
                <?php $reserved_data = json_encode($dashboard_data[3][0]->device_count); ?>;
                <?php $broken_data = json_encode($dashboard_data[4][0]->device_count); ?>;
                <?php $maintenance_data = json_encode($dashboard_data[5][0]->device_count); ?>;
            
                // Pie Chart
                // Create chart instance
                var chart = am4core.create("device_types_pie_div", am4charts.PieChart);

                chart.data = pie_data;

                var pieSeries = chart.series.push(new am4charts.PieSeries());
                pieSeries.dataFields.value = "device_count";
                pieSeries.dataFields.category = "dev_model";

                // Add legend
                chart.legend = new am4charts.Legend();

                // Responsive
                chart.responsive.enabled = true;
                chart.responsive.rules.push({
                relevant: function(target) {
                    if (target.pixelWidth <= 600) {
                    return true;
                    }
                    return false;
                },
                state: function(target, stateId) {
                    if (target instanceof am4charts.PieSeries) {
                    var state = target.states.create(stateId);

                    var labelState = target.labels.template.states.create(stateId);
                    labelState.properties.disabled = true;

                    var tickState = target.ticks.template.states.create(stateId);
                    tickState.properties.disabled = true;
                    return state;
                    }

                    return null;
                }
                });

    </script>

    <div class="detail-flex">
    

        
        <div class="detail-container">
            <div class="d-detail-container">
                <!-- str_replace removes the double quotes from the echoed data -->
                <h2>In Storage</h2>
                <p><?=str_replace('"', '', $device_in_data)?></p>
            </div>
            <div class="d-detail-container">
                <h2>Released</h2>
                <p><?=str_replace('"', '', $device_out_data)?></p>
            </div>
            <div class="d-detail-container">
                <h2>Reserved</h2>
                <p><?=str_replace('"', '', $reserved_data)?></p>
            </div>
            <div class="d-detail-container">
                <h2>Broken</h2>
                <p><?=str_replace('"', '', $broken_data)?></p>
            </div>
            <div class="d-detail-container">
                <h2>Maintenance</h2>
                <p><?=str_replace('"', '', $maintenance_data)?></p>
            </div>
        </div>

        <div class="pie-container">
            <div id="device_types_pie_div"></div>
            
        </div>


    </div>

    
    
</div>
