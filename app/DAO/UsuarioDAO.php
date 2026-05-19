<?php

class UsuarioDAO extends DAO {

    public function salvar(Usuario $usuario): void {
        $stmt = $this->conn->prepare(
            "INSERT INTO usuario (nome, email, senha_hash, cargo, ativo)
             VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $usuario->getNome(),
            $usuario->getEmail(),
            $usuario->getSenhaHash(),
            $usuario->getCargo(),
            $usuario->getAtivo() ? 'true' : 'false',
        ]);
    }

    public function listar(): array {
        $stmt = $this->conn->query("SELECT * FROM usuario ORDER BY nome");
        $usuarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $usuarios[] = $this->hydrate($row);
        }
        return $usuarios;
    }

    public function buscarPorId(int $id): ?Usuario {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function buscarPorEmail(string $email): ?Usuario {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->hydrate($row) : null;
    }

    public function atualizar(Usuario $usuario): void {
        $stmt = $this->conn->prepare(
            "UPDATE usuario SET nome = ?, email = ?, cargo = ?, ativo = ? WHERE id = ?"
        );
        $stmt->execute([
            $usuario->getNome(),
            $usuario->getEmail(),
            $usuario->getCargo(),
            $usuario->getAtivo() ? 'true' : 'false',
            $usuario->getId(),
        ]);
    }

    public function deletar(int $id): void {
        $stmt = $this->conn->prepare("DELETE FROM usuario WHERE id = ?");
        $stmt->execute([$id]);
    }

    private function hydrate(array $row): Usuario {
        $u = new Usuario();
        $u->setId($row['id']);
        $u->setNome($row['nome']);
        $u->setEmail($row['email']);
        $u->setSenhaHash($row['senha_hash']);
        $u->setCargo(constant(Cargo::class . '::' . $row['cargo']));
        $u->setAtivo($row['ativo'] === 't' || $row['ativo'] === true);
        return $u;
    }
}
