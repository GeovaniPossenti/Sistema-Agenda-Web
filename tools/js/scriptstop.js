			function habilitarSelectPermissao(){	
				var selectContatos = document.getElementById("selectContatos").value;	
				var selectPermissao = document.getElementById("selectPermissao");
				var labelselectPermissao = document.getElementById("labelselectPermissao");
				if(selectContatos != ""){
					selectPermissao.disabled=false;
					selectPermissao.hidden=false;
					labelselectPermissao.hidden=false;
				}else{
					selectPermissao.disabled=true;
					selectPermissao.hidden=true;
					labelselectPermissao.hidden=true;
				}
			}
			function habilitarSelectPermissao2(){	
				var selectContatos = document.getElementById("selectContatosEditar").value;	
				var selectPermissao = document.getElementById("selectPermissaoEditar");
				var labelselectPermissao = document.getElementById("labelselectPermissaoEditar");

				if(selectContatos != ""){
					selectPermissao.disabled=false;
					selectPermissao.hidden=false;
					labelselectPermissao.hidden=false;
				}else{
					selectPermissao.disabled=true;
					selectPermissao.hidden=true;
					labelselectPermissao.hidden=true;
				}
            }
            //Cadastro de eventos! ---------------------------------------------------------
			function validarData(){
				var startdate = document.getElementById('startdateCadastro').value;
				var enddate = document.getElementById('enddateCadastro').value;
				var botaoCadastra = document.getElementById('botaoCadastra');
				var labelDataInvalida1 = document.getElementById('labelDataInvalida1');
				var labelDataInvalida2 = document.getElementById('labelDataInvalida2');

				if(startdate == enddate){
					botaoCadastra.disabled = false;
					labelDataInvalida1.hidden = true;
					labelDataInvalida2.hidden = true;
					console.log("default1");
					document.getElementById('botaoCadastra').className = 'btn btn-success';
				}else if(startdate > enddate && enddate < startdate){
					document.getElementById('botaoCadastra').className = 'btn btn-secondary';
					botaoCadastra.disabled = true;
					console.log("1");
					labelDataInvalida1.hidden = false;
				}else{
					document.getElementById('botaoCadastra').className = 'btn btn-success';
					botaoCadastra.disabled = false;
					console.log("agrvai");
					labelDataInvalida1.hidden = true;
					labelDataInvalida2.hidden = true;
					labelHoraInvalida1.hidden = true;
					labelHoraInvalida2.hidden = true;
				}
            }
            
			function validarData2(){
				var startdate = document.getElementById('startdateCadastro').value;
				var enddate = document.getElementById('enddateCadastro').value;
				var botaoCadastra = document.getElementById('botaoCadastra');
				var labelDataInvalida1 = document.getElementById('labelDataInvalida1');
				var labelDataInvalida2 = document.getElementById('labelDataInvalida2');

				if(enddate == startdate){
					document.getElementById('botaoCadastra').className = 'btn btn-success';
					botaoCadastra.disabled = false;
					labelDataInvalida1.hidden = true;
					labelDataInvalida2.hidden = true;
					console.log("default1");
				}else if(enddate < startdate && startdate > enddate){
					document.getElementById('botaoCadastra').className = 'btn btn-secondary';
					botaoCadastra.disabled = true;
					console.log("1");
					labelDataInvalida2.hidden = false;
				}else{
					document.getElementById('botaoCadastra').className = 'btn btn-success';
					botaoCadastra.disabled = false;
					console.log("agrvai");
					labelDataInvalida1.hidden = true;
					labelDataInvalida2.hidden = true;
					labelHoraInvalida1.hidden = true;
					labelHoraInvalida2.hidden = true;
				}
            }

            //Edicao de eventos! ---------------------------------------------------------
			function validarDataEditar(){
				var startdate = document.getElementById('startdate').value;
				var enddate = document.getElementById('enddate').value;
				var botaoformEditar = document.getElementById('botaoformEditar');
				var labelDataInvalidaEditar1 = document.getElementById('labelDataInvalidaEditar1');
                var labelDataInvalidaEditar2 = document.getElementById('labelDataInvalidaEditar2');

				if(startdate == enddate){
					botaoformEditar.disabled = false;
					labelDataInvalidaEditar1.hidden = true;
					labelDataInvalidaEditar2.hidden = true;
					console.log("default1");
					document.getElementById('botaoformEditar').className = 'btn btn-success';
				}else if(startdate > enddate && enddate < startdate){
					document.getElementById('botaoformEditar').className = 'btn btn-secondary';
					botaoformEditar.disabled = true;
					console.log("1");
					labelDataInvalidaEditar1.hidden = false;
				}else{
					document.getElementById('botaoformEditar').className = 'btn btn-success';
					botaoformEditar.disabled = false;
					console.log("agrvai1");
					labelDataInvalidaEditar1.hidden = true;
					labelDataInvalidaEditar2.hidden = true;
					labelHoraInvalidaEditar1.hidden = true;
					labelHoraInvalidaEditar2.hidden = true;
				}
			}
			function validarDataEditar2(){
				var startdate = document.getElementById('startdate').value;
				var enddate = document.getElementById('enddate').value;
				var botaoformEditar = document.getElementById('botaoformEditar');
				var labelDataInvalidaEditar1 = document.getElementById('labelDataInvalidaEditar1');
				var labelDataInvalidaEditar2 = document.getElementById('labelDataInvalidaEditar2');
				
				if(enddate == startdate){
					document.getElementById('botaoformEditar').className = 'btn btn-success';
					botaoformEditar.disabled = false;
					labelDataInvalidaEditar1.hidden = true;
					labelDataInvalidaEditar2.hidden = true;
					console.log("default1");
				}else if(enddate < startdate && startdate > enddate){
					document.getElementById('botaoformEditar').className = 'btn btn-secondary';
					botaoformEditar.disabled = true;
					console.log("1");
					labelDataInvalidaEditar2.hidden = false;
				}else{
					document.getElementById('botaoformEditar').className = 'btn btn-success';
					botaoformEditar.disabled = false;
					console.log("agrvai");
					labelDataInvalidaEditar1.hidden = true;
					labelDataInvalidaEditar2.hidden = true;
					labelHoraInvalidaEditar1.hidden = true;
					labelHoraInvalidaEditar2.hidden = true;
				}
			}

			function validarHora(){
				//Datas.
				var startdateCadastro = document.getElementById('startdateCadastro').value;
				var enddateCadastro = document.getElementById('enddateCadastro').value;

				//Times.
				var starttimeCadastro = document.getElementById('starttimeCadastro').value;
				var endtimeCadastro = document.getElementById('endtimeCadastro').value;

				//Botao e labels.
				var botaoCadastra = document.getElementById('botaoCadastra');
				var labelHoraInvalida1 = document.getElementById('labelHoraInvalida1');
				var labelHoraInvalida2 = document.getElementById('labelHoraInvalida2');

				if(starttimeCadastro == endtimeCadastro && startdateCadastro == enddateCadastro){
					botaoCadastra.disabled = true;
					labelHoraInvalida1.hidden = false;
					labelHoraInvalida2.hidden = false;
					document.getElementById('botaoCadastra').className = 'btn btn-secondary';

					console.log("1Hora");
				}else if(starttimeCadastro < endtimeCadastro && startdateCadastro == enddateCadastro){
					botaoCadastra.disabled = false;
					labelHoraInvalida1.hidden = true;
					labelHoraInvalida2.hidden = true;
					document.getElementById('botaoCadastra').className = 'btn btn-success';

					console.log("2Hora");
				}else if (starttimeCadastro > endtimeCadastro && startdateCadastro == enddateCadastro){
					botaoCadastra.disabled = true;
					labelHoraInvalida1.hidden = false;
					labelHoraInvalida2.hidden = false;
					document.getElementById('botaoCadastra').className = 'btn btn-secondary';

					console.log("3Hora");
				}
			}
			function validarHoraEditar(){
				//Datas.
				var startdate = document.getElementById('startdate').value;
				var enddate = document.getElementById('enddate').value;

				//Times.
				var starttime = document.getElementById('starttime').value;
				var endtime = document.getElementById('endtime').value;

				//Botao e labels.
				var botaoformEditar = document.getElementById('botaoformEditar');
				var labelHoraInvalidaEditar1 = document.getElementById('labelHoraInvalidaEditar1');
				var labelHoraInvalidaEditar2 = document.getElementById('labelHoraInvalidaEditar2');

				if(starttime == endtime && startdate == enddate){
					botaoformEditar.disabled = true;
					labelHoraInvalidaEditar1.hidden = false;
					labelHoraInvalidaEditar2.hidden = false;
					document.getElementById('botaoformEditar').className = 'btn btn-secondary';

					console.log("1Hora");
				}else if(starttime < endtime && startdate == enddate){
					botaoformEditar.disabled = false;
					labelHoraInvalidaEditar1.hidden = true;
					labelHoraInvalidaEditar2.hidden = true;
					document.getElementById('botaoformEditar').className = 'btn btn-success';

					console.log("2Hora");
				}else if (starttime > endtime && startdate == enddate){
					botaoformEditar.disabled = true;
					labelHoraInvalidaEditar1.hidden = false;
					labelHoraInvalidaEditar2.hidden = false;
					document.getElementById('botaoformEditar').className = 'btn btn-secondary';

					console.log("3Hora");
				}
			}
            
            