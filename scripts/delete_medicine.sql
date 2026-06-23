-- Delete one medicine and its dependent rows.
-- Use this in DBeaver after changing @medicine_id.
--
-- Notes:
-- - carts rows are safe to delete because they are temporary cart items.
-- - order_details rows are order history. Deleting them removes that medicine
--   from past orders, so only use this for demo/sample data cleanup.
-- - If you only want to hide a medicine from the storefront, use:
--   UPDATE medicines SET status = 'inactive' WHERE id = 123;

START TRANSACTION;

SET @medicine_id := 123;

SELECT id, name, image_url
FROM medicines
WHERE id = @medicine_id;

DELETE FROM carts
WHERE medicine_id = @medicine_id;

DELETE FROM order_details
WHERE medicine_id = @medicine_id;

DELETE FROM medicines
WHERE id = @medicine_id;

COMMIT;
