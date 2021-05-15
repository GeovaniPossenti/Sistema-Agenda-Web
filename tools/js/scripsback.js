		//Script que faz a movimentacao do modal de editar
			$('#formEditar').hide();
			$('.btn-canc-vis').on("click", function() {
				$('#formEditar').slideToggle();
				$('.visualizar').slideToggle();
				$('.visualizar').hide();

			});
			$('.btn-canc-edit').on("click", function() {
				$('.visualizar').slideToggle();
				$('.visualizar').show();
				$('#formEditar').slideToggle();
				$('#formEditar').hide();
			});
			//Aqui eu identifico que o modal de editar eventos fechou.
			$('#visualizar').on('hide.bs.modal', function () {
				$('#formEditar').slideToggle();
				$('#formEditar').hide(); 
				$('.visualizar').show();
				var botaoformEditar = document.getElementById('botaoformEditar');
				var labelDataInvalidaEditar1 = document.getElementById('labelDataInvalidaEditar1');
				var labelDataInvalidaEditar2 = document.getElementById('labelDataInvalidaEditar2');
				botaoformEditar.disabled = false;
				labelDataInvalidaEditar1.hidden = true;
				labelDataInvalidaEditar2.hidden = true;
				document.getElementById('botaoformEditar').className = 'btn btn-success';
			});
        
        // --------------------------------------------------------- 

		//Header titles aumentando com mouseover
			$(document).ready(function(){
				$("#logotipo").on("mouseover",function(){
					$("#logotipo").addClass("efeito-logotipo");
				}).on("mouseout", function(){
					$("#logotipo").removeClass("efeito-logotipo");
				});
				$(".header-ul1").on("mouseover",function(){
					$(".header-ul1").addClass("efeito-title");
				}).on("mouseout", function(){
					$(".header-ul1").removeClass("efeito-title");
				});
				$(".header-ul2").on("mouseover",function(){
					$(".header-ul2").addClass("efeito-title");
				}).on("mouseout", function(){
					$(".header-ul2").removeClass("efeito-title");
				});
				$(".header-ul3").on("mouseover",function(){
					$(".header-ul3").addClass("efeito-title");
				}).on("mouseout", function(){
					$(".header-ul3").removeClass("efeito-title");
				});
				$(".header-ul4").on("mouseover",function(){
					$(".header-ul4").addClass("efeito-title");
				}).on("mouseout", function(){
					$(".header-ul4").removeClass("efeito-title");
				});
				$(".header-ul5").on("mouseover",function(){
					$(".header-ul5").addClass("efeito-title");
				}).on("mouseout", function(){
					$(".header-ul5").removeClass("efeito-title");
				});
				$(".foto-perfil").on("mouseover",function(){
					$(".foto-perfil").addClass("efeito-foto-usuario");
				}).on("mouseout", function(){
					$(".foto-perfil").removeClass("efeito-foto-usuario");
				});
			});
        
        // --------------------------------------------------------- 
        
		//Para o autofocus funcionar nos modais do bootstrap.
			//Autofocus do modal de cadastro.
			$('#cadastrar').on('shown.bs.modal', function () {
				$('#nomeEvento').trigger('focus');
			});
			//Para dar um default (MODAL DE CADASTRO DE EVENTOS).
			$('#cadastrar').on('hide.bs.modal', function () {
				var botaoCadastra = document.getElementById('botaoCadastra');
				var labelDataInvalida1 = document.getElementById('labelDataInvalida1');
				botaoCadastra.disabled = false;
				labelDataInvalida1.hidden = true;
				labelDataInvalida2.hidden = true;
				document.getElementById('botaoCadastra').className = 'btn btn-success';
			});
			//Autofocus do modal de cadastro de contatos.
			$('#cadastrarContatos').on('shown.bs.modal', function () {
				$('#inputNomeContato').trigger('focus')
			});
			//Autofocus do modal de editar contatos.
			$('#editarContatos').on('shown.bs.modal', function () {
				$('#inputNomeContatoEditar').trigger('focus')
			});
		
		//Para ele nao conseguir digitar enter, no textarea.
		$('#textarea').keypress(function(event) {
			if (event.keyCode == 13) {
				event.preventDefault();
			}
		});
		$('.desc').keypress(function(event) {
			if (event.keyCode == 13) {
				event.preventDefault();
			}
		});
		$('.submit_on_enter').keydown(function(event) {
			//Enter code = 13.
			if (event.keyCode == 13) {
			  this.form.submit();
			  return false;
			}
		});