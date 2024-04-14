<?php
class BookCest
{
    public function _before(FunctionalTester $I)
    {
        // Здесь можно выполнить инициализацию перед каждым тестом
    }

    public function _after(FunctionalTester $I)
    {
        // Здесь можно выполнить очистку после каждого теста
    }

    // Тест фильтрации списка книг по названию
    public function testFilterBooksByTitle(FunctionalTester $I)
    {
        $I->haveInDatabase('books', [
            'title' => 'Unique Book Title',
            'author_id' => 1,
            'page_count' => 100,
            'language' => 'English',
            'genre' => 'Science'
        ]);

        $I->sendGET('/books', ['title' => 'Unique Book Title']);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'title' => 'Unique Book Title'
        ]);
    }
}
