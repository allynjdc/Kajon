if(data != ''){
                var allfiles = '';
                      $.each(data[0], function(key, value){
                        console.log(value["filename"]);

                       var updated_at = value["updated_at"];
                        allfiles +=  '<li class="drawer-item waves-effect" data-public="{{ ($allfile->public OR $allfile->shared_by_admin)?1:0 }}" data-file-id="'+ value["id"] + '" data-owned-by-user= "1">' +
                            '<i class="material-icons">' + 'description' + '</i>' +
                           ' <div>' + 
                                '<p class="title truncate">' +  value["filename"] + '</p>' + 
                                '<span class="date">' + updated_at + '</span>' +
                            '</div>' +
                            '<p class="description">'+
                                 value["description"]  + 
                            '</p>'+
                        '</li>';

                      });    

                      // $("#all_files").html(allfiles);



                    if (num >= limit) {
                        console.log('num = ' + num + '      limit = ' + limit);
                        btn = '<a href="#" data-id="{{ $allfile->id }}" id="btn-more" class="waves-effect waves-light">'+
                                '<span class="plus-icon">'+' +'+' </span>'+
                                'See more'+'</a>';

                        $('#btn-more').html(btn);
                    }
                    else{
                        console.log('num = ' + num + '      limit = ' + limit);

                        $('#remove-row').css("display", "none");
                    }

                      $(allfiles).insertBefore("#remove-row");
                }
                else{
                        $('#remove-row').css("display", "none");

                }