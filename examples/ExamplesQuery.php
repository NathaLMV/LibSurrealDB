<?php

namespace natha\examples;

use natha\surrealdb\SurrealAPI;
use natha\surrealdb\utils\PromiseHandler;
use pocketmine\Server;

class ExampleQueries {

    public static function runAllExamples(): void {
        $q = SurrealAPI::queries();

        $q->createTable("jugadores", self::class, "handleResult");
        $q->insertData("jugadores", ["nombre"=>"Alex","puntos"=>100], self::class, "handleResult");
        $q->selectData("jugadores", self::class, "handleResult", "WHERE puntos > 50");
        $q->update("jugadores", ["puntos"=>200], "WHERE nombre='Alex'", self::class, "handleResult");
        $q->patch("jugadores", [["op"=>"replace","path"=>"/puntos","value"=>250]], "WHERE nombre='Alex'", self::class, "handleResult");
        $q->delete("jugadores", "WHERE nombre='Alex'", self::class, "handleResult");
        $q->count("jugadores", "", self::class, "handleResult");
        $q->dropTable("jugadores", self::class, "handleResult");
        $q->alterTable("jugadores", "SCHEMAFULL", self::class, "handleResult");
        $q->defineIndex("jugadores","puntos","idx_puntos",self::class,"handleResult");
        $q->removeIndex("jugadores","idx_puntos",self::class,"handleResult");
        $q->defineFunction(
            "calcularPuntos",
            '$x: number, $y: number',
            '{ RETURN $x + $y; }',
            self::class,
            "handleResult"
        );
        $q->runTransaction([
            "INSERT INTO jugadores { nombre:'Lina', puntos:70 };",
            "INSERT INTO jugadores { nombre:'Kai', puntos:80 };"
        ], self::class, "handleResult");
        $q->rawQuery("SELECT * FROM jugadores;", self::class, "handleResult");
        $q->info(self::class, "handleResult");
        $q->healthCheck(self::class, "handleResult");
    }

    public static function handleResult(array $result): void {
        var_dump($result);
        PromiseHandler::handle($result,
            function(array $rows, array $extra) {
                Server::getInstance()->getLogger()->info("SUCCESS: " . json_encode($rows));
            },
            function(string $error, array $extra) {
                Server::getInstance()->getLogger()->error("ERROR: " . $error);
            }
        );
    }
}
