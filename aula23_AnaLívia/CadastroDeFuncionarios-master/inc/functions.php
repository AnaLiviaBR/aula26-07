<?php   //quando seu arquivo tem só php, não precisa fechar a tah do php

		// Definindo uma constante para o nome do arquivo
		define('ARQUIVO','funcionarios.json');

		// Função para validar dados do post
		function errosNoPost(){
			$erros =[];
			if(!isset($_POST['nome']) || $_POST['nome']==''){
				$erros[] = 'errNome';
			}
	
			if(!isset($_POST['email']) || $_POST['email']==''){
				$erros[] = 'errEmail';
			}
	
			if(!isset($_POST['senha']) || $_POST['senha']==''){
				$erros[] = 'errSenha';
			}
	
			if($_POST['conf'] != $_POST['senha']){   //Se a confirmação da senha for diferente da senha, dê erro(errConf)//
				$erros[] = 'errConf';
			}
	
			return $erros;
		}
	
		// Carregando o conteúdo do arquivo (string json) para uma variável
		function getFuncionarios(){
			$json = file_get_contents(ARQUIVO);
			$funcionarios = json_decode($json,true);
			return $funcionarios;
		}
		
		// Função que adiciona funcionario ao json
		function addFuncionario($nome,$email,$senha){
	
			// Carregando os funcionarios
			$funcionarios = getFuncionarios();
	
			// Adicionando um novo funcionario ao array de funcionarios
			$funcionarios[] = [
				'nome' => $nome,
				'email' => $email,
				'senha' => password_hash($senha, PASSWORD_DEFAULT)         //a função password_hash criptografa a senha do usuário ($senha) e guarda no banco de dados um código muito louco que não tem como descobrir a que se refere. outra função segura (até agora) para crptopgrafar é sha1
			];
			
			// Transformando o array funcionarios numa string json
			$json = json_encode($funcionarios);
	
			// Salvar a string json no arquivo
			file_put_contents(ARQUIVO,$json); 
		}

		//Função para verificar se o loguin é válido
		function logar($email, $senha){

			//Carregar funcionários que estão no arquivo Json
			$funcionarios = getFuncionarios();

			//Procurar o funcionário com o e-mail dado
			$achou = false;
			foreach ($funcionarios as $f) {
				if ($f['email'] == $email){
					$achou = true;
					break;
				}
			}

			if(!$achou){
				return false;
			} else {
				$senhaOk = password_verify($senha, $f['senha']);
				return $senhaOk;
			}
		}