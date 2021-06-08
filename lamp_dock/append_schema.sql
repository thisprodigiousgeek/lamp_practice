
-- 購入履歴画面テーブル
CREATE TABLE history(
    history_id INT AUTO_INCREMENT,
    user_id INT,
    created DATETIME,
    primary key(history_id)
);

-- 購入明細画面テーブル
CREATE TABLE details(
    history_id INT,
    item_id INT,
    price INT,
    amount INT
);

-- ・ なぜテーブルが二つ必要か
-- 分けたテーブルは繰り返し項目なので分離して、IDをつけることにより
-- 整合性を保ったまま、重複のデータが減り、余分なものなどを少なくする（冗長性が低下）。


-- ・ 正規化とはどういうことか
-- 無駄なデータを持たないように整理すること


-- ・ 正規化の観点からすると、priceは不要な理由
-- 購入の際に同じ値段を何度も繰り返すことは無駄なデータを持つことになるから


--・正規化の観点からpriceは不要であるはずなのに、ここではあえてpriceを入れている理由は何か。
-- 合計金額をわかりやすくできるため
