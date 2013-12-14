var baseUri = $('base').attr('href').replace('/app/','');        
$(function(){
    //baseUri
    $('head').append('<script src="js/jquery/jquery.placeholder.js" type="text/javascript"></script>');
    $('input[placeholder], textarea[placeholder]').placeholder();    
    //tootips
    $('.tips-top').tooltip({
        placement:'top'
    });    
    $('.tips-left').tooltip({
        placement:'left'
    });    
    $('.tips-bottom').tooltip({
        placement:'bottom'
    });    
    $('.tips-right').tooltip({
        placement:'right'
    }); 
    
    $('.elm_preco').each(function(){ 
        if($(this).attr('v') == "0,00")
            $(this).html('Consulte-nos');
    })
    $('.tipofilter').live('click',function(e){
        e.preventDefault();
        $('.tipos a').removeClass('active');
        $(this).addClass('active');
        var tipo = $(this).attr('id');
        if(tipo == 'all'){
            $('.imovel-list').find('.tipo').fadeIn()    
        }
        else{
            $('.imovel-list .tipo').hide()
            $('.imovel-list').find('.'+tipo).fadeIn()
        }
        $('html, body').animate({
            scrollTop: $('.panel-destaque').offset().top
        }, 1000);
    })
    $('#finalidade').live('change',function(e){
        e.preventDefault();
        if($('#finalidade option:selected').val() == 2 || $('#finalidade option:selected').val() == 4){
            $('.valor_venda').hide();
            $('.valor_locacao').show();
        }
        else{
            $('.valor_locacao').hide();
            $('.valor_venda').show();
        }
             
    })
    $('#search').submit(function(e){
        if( $('#busca').val() == ""){
            $('#busca').focus();
            return false;
        }
    })
    $('#panel-busca form').attr('onSubmit','return false');
    $('#btn-busca').live('click',function(e){
        e.preventDefault();
        var finalidade = $('#finalidade option:selected').val();
        var tipo = $('#tipo option:selected').val();
        var dorms = $('#dorms option:selected').val();
        var cidade = $('#cidade option:selected').val();
        var valormax = $('#valormax option:selected').val();
        
        var url  = baseUri+'/index/buscaavancada/';
        url += 'finalidade/'+finalidade+'/';
        
        if(tipo >= 1){
            url += 'tipo/'+tipo+'/';
        }
        if(dorms >= 1){
            url += 'dorms/'+dorms+'/';
        }
        if(cidade >= 1){
            url += 'cidade/'+cidade+'/';
        }
        if(valormax >= 1){
            url += 'max/'+valormax+'/';
        }
        if(finalidade > 0){
            window.location = url;
        }
        else{
            var popcontent = '<p>Você deve informar o tipo de transação.<p>';
            $('#finalidade').popover({
                placement:'right',
                title:'Busca Refinada',
                html: true, 
                content:popcontent
            });    
            $('#finalidade').popover('show');           
        }
    })
    $('.bot-panel').toggle(function(){
        $('#top-search').slideUp();
        $('#panel-busca').slideDown();
        $('.bot-panel').find('p').html('<i class="icon-search icon-white"></i> Busca Simples');
    },
    function(){
        $('#panel-busca').slideUp();
        $('#top-search').slideDown();
        $('.bot-panel').find('p').html('<i class="icon-search icon-white"></i> Busca Avançada');
        $('#finalidade').popover('hide');  
    })
         
    $('.show-map').live('click',function(){
        $('body').css('overflow','hidden');        
        $("#about-info").hide();
        var address = $('#address').html();       
        $("#about-info").hide(); 
        $("#fale-conosco").hide();
        $("#location-info").show();  
        $('html, body').animate({
            scrollTop: $('#footer').offset().top
        }, 1000,function(){
            setMapAddr("mini-map",address,16);  
        });        
    })
    $('.show-about').live('click',function(){
        $('body').css('overflow','hidden');        
        $("#mini-map").html('');  
        $("#location-info").hide();         
        $("#fale-conosco").hide();
        $("#about-info").show();  
        $('html, body').animate({
            scrollTop: $('#footer').offset().top
        }, 1000);        
    })
    $('.fale-conosco').live('click',function(){
        $("#mini-map").html('');  
        $("#location-info").hide();         
        $("#about-info").hide();  
        $("#fale-conosco").show();
        $('html, body').animate({
            scrollTop: $('#footer').offset().top
        }, 1000);        
        $('body').css('overflow','hidden');        
        $('#form-contato-home .btn').button();
        $('#form-contato-home').attr('onSubmit','return false');
        $('#form-contato-home').submit(function(e) {
            e.preventDefault();
            e.stopPropagation();
            if(valid == true)
            {
                $('#form-contato-home .btn').button('loading');
                var url = baseUri + '/atendimento/send/';
                var ar  = $(this).serialize();
                $.post(url,{
                    ar:ar
                },function(data){
                    if(data == 0){
                        $('#form-contato-home .btn').removeClass('btn-danger').addClass('btn-success');
                        $('#form-contato-home .btn').button('complete');
                        $('#form-contato-home').attr('onsubmit','return false');
                        $('#form-contato-home').find('*').val('');
                        $('#form-contato-home').find('*').removeClass('invalid').removeClass('valid');                    
                        setTimeout(function(){
                            $('#form-contato-home .btn').addClass('btn-danger').removeClass('btn-success');    
                            $('#form-contato-home .btn').html('Enviar Nova Mensagem');
                        },5000);
                    }
                })
            }
            return false;
        })
        return false;        
    })
    
    $('.go-top img').tooltip({
        placement:'top'
    }); 
    $('.go-top').live('click',function(){
        $("#about-info").hide();  
        $("#mini-map").html('');  
        $("#location-info").hide();  
        $("#fale-conosco").hide();
        $('body').css('overflow','auto');
        $('html, body').animate({
            scrollTop: $('#logo').offset().top
        }, 700);  
        $('#form-contato-home').find('*').removeClass('invalid').removeClass('valid'); 
        $('#contato-ref-im').find('*').removeClass('invalid').removeClass('valid'); 
    })
    
    $('#contato-ref-im').attr('onSubmit','return false');
    $('#contato-ref-im').submit(function(e) {
        e.preventDefault();
        e.stopPropagation();
        if(valid == true)
        {
            $('#contato-ref-im .btn').button('loading');
            var url = baseUri + '/atendimento/sendFromRef/';
            var ar  = $(this).serialize();
            $.post(url,{
                ar:ar
            },function(data){
                if(data == 0){
                    $('#contato-ref-im .btn').removeClass('btn-danger').addClass('btn-success');
                    $('#contato-ref-im .btn').button('complete');
                    $('#contato-ref-im').attr('onsubmit','return false');
                    $('#contato-ref-im').find('.required').val('');
                    $('#contato-ref-im').find('*').removeClass('invalid').removeClass('valid');                    
                    setTimeout(function(){
                        $('#contato-ref-im .btn').addClass('btn-danger').removeClass('btn-success');    
                        $('#contato-ref-im .btn').html('Enviar Nova Mensagem');
                    },5000);
                }
            })
        }
        return false;
    })
})

function goToContato() {    
 $('.fale-conosco').click();     
}
function goToMapa() {    
 $('.show-map').click();     
}
function goToInstitucional() {    
 $('.show-about').click();     
}