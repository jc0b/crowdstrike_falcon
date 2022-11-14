<h2 data-i18n="crowdstrike_falcon.title"></h2>
<div id="crowdstrike_falcon-tab"></div>

<div id="crowdstrike_falcon-msg" data-i18n="listing.loading" class="col-lg-12 text-center"></div>

<script>
$(document).on('appReady', function(){
	$.getJSON(appUrl + '/module/crowdstrike_falcon/get_data/' + serialNumber, function(data){
        // Check if we have data
        console.log(data)
	if(!data[0].sensor_version && data[0].sensor_version !== null && data[0].sensor_version !== 0){
            $('#crowdstrike_falcon-msg').text(i18n.t('no_data'));
            $('#crowdstrike_falcon-header').removeClass('hide');

        } else {

            // Hide
            $('#crowdstrike_falcon-msg').text('');
            $('#crowdstrike_falcon-view').removeClass('hide');

            var crowdstrike_region = "<?php configAppendFile(__DIR__ . '/../config.php'); echo rtrim(conf('crowdstrike_region'), '/'); ?>"; // Get the Kandji server address

            var skipThese = ['id','serial_number'];
            $.each(data, function(i,d){

                // Generate rows from data
                var rows = ''
                for (var prop in d){
                    // Skip skipThese
                    if(skipThese.indexOf(prop) == -1){
                        // Do nothing for empty values to blank them
                        if (d[prop] == '' || d[prop] == null){
                            rows = rows

                        // Format date
                        } else if((prop == "agent_id") && d[prop].length > 0){
                            rows = rows + '<tr><th>'+i18n.t('crowdstrike_falcon.'+prop)+'</th><td><a href="https://'+crowdstrike_region+'.crowdstrike.com/hosts/hosts/host/'+d[prop].toLowerCase()+'">'+d[prop]+'</a></td></tr>';
                        } else {
                            rows = rows + '<tr><th>'+i18n.t('crowdstrike_falcon.'+prop)+'</th><td>'+d[prop]+'</td></tr>';
                        }
                    }
                }

                $('#crowdstrike_falcon-tab')
                    .append($('<div style="max-width:600px;">')
                        .append($('<table>')
                            .addClass('table table-striped table-condensed')
                            .append($('<tbody>')
                                .append(rows))))
            })
        }
	});
});
</script>
