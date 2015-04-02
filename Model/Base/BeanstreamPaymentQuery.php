<?php

namespace BeanstreamModule\Model\Base;

use \Exception;
use \PDO;
use BeanstreamModule\Model\BeanstreamPayment as ChildBeanstreamPayment;
use BeanstreamModule\Model\BeanstreamPaymentQuery as ChildBeanstreamPaymentQuery;
use BeanstreamModule\Model\Map\BeanstreamPaymentTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'beanstream_payment' table.
 *
 *
 *
 * @method     ChildBeanstreamPaymentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildBeanstreamPaymentQuery orderByOrderId($order = Criteria::ASC) Order by the order_id column
 * @method     ChildBeanstreamPaymentQuery orderByMessageId($order = Criteria::ASC) Order by the message_id column
 * @method     ChildBeanstreamPaymentQuery orderByMessage($order = Criteria::ASC) Order by the message column
 *
 * @method     ChildBeanstreamPaymentQuery groupById() Group by the id column
 * @method     ChildBeanstreamPaymentQuery groupByOrderId() Group by the order_id column
 * @method     ChildBeanstreamPaymentQuery groupByMessageId() Group by the message_id column
 * @method     ChildBeanstreamPaymentQuery groupByMessage() Group by the message column
 *
 * @method     ChildBeanstreamPaymentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildBeanstreamPaymentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildBeanstreamPaymentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildBeanstreamPayment findOne(ConnectionInterface $con = null) Return the first ChildBeanstreamPayment matching the query
 * @method     ChildBeanstreamPayment findOneOrCreate(ConnectionInterface $con = null) Return the first ChildBeanstreamPayment matching the query, or a new ChildBeanstreamPayment object populated from the query conditions when no match is found
 *
 * @method     ChildBeanstreamPayment findOneById(int $id) Return the first ChildBeanstreamPayment filtered by the id column
 * @method     ChildBeanstreamPayment findOneByOrderId(int $order_id) Return the first ChildBeanstreamPayment filtered by the order_id column
 * @method     ChildBeanstreamPayment findOneByMessageId(int $message_id) Return the first ChildBeanstreamPayment filtered by the message_id column
 * @method     ChildBeanstreamPayment findOneByMessage(string $message) Return the first ChildBeanstreamPayment filtered by the message column
 *
 * @method     array findById(int $id) Return ChildBeanstreamPayment objects filtered by the id column
 * @method     array findByOrderId(int $order_id) Return ChildBeanstreamPayment objects filtered by the order_id column
 * @method     array findByMessageId(int $message_id) Return ChildBeanstreamPayment objects filtered by the message_id column
 * @method     array findByMessage(string $message) Return ChildBeanstreamPayment objects filtered by the message column
 *
 */
abstract class BeanstreamPaymentQuery extends ModelCriteria
{

    /**
     * Initializes internal state of \BeanstreamModule\Model\Base\BeanstreamPaymentQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'thelia', $modelName = '\\BeanstreamModule\\Model\\BeanstreamPayment', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildBeanstreamPaymentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildBeanstreamPaymentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \BeanstreamModule\Model\BeanstreamPaymentQuery) {
            return $criteria;
        }
        $query = new \BeanstreamModule\Model\BeanstreamPaymentQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildBeanstreamPayment|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = BeanstreamPaymentTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(BeanstreamPaymentTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return   ChildBeanstreamPayment A model object, or null if the key is not found
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, ORDER_ID, MESSAGE_ID, MESSAGE FROM beanstream_payment WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildBeanstreamPayment();
            $obj->hydrate($row);
            BeanstreamPaymentTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildBeanstreamPayment|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return ChildBeanstreamPaymentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(BeanstreamPaymentTableMap::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return ChildBeanstreamPaymentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(BeanstreamPaymentTableMap::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBeanstreamPaymentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(BeanstreamPaymentTableMap::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(BeanstreamPaymentTableMap::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BeanstreamPaymentTableMap::ID, $id, $comparison);
    }

    /**
     * Filter the query on the order_id column
     *
     * Example usage:
     * <code>
     * $query->filterByOrderId(1234); // WHERE order_id = 1234
     * $query->filterByOrderId(array(12, 34)); // WHERE order_id IN (12, 34)
     * $query->filterByOrderId(array('min' => 12)); // WHERE order_id > 12
     * </code>
     *
     * @param     mixed $orderId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBeanstreamPaymentQuery The current query, for fluid interface
     */
    public function filterByOrderId($orderId = null, $comparison = null)
    {
        if (is_array($orderId)) {
            $useMinMax = false;
            if (isset($orderId['min'])) {
                $this->addUsingAlias(BeanstreamPaymentTableMap::ORDER_ID, $orderId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderId['max'])) {
                $this->addUsingAlias(BeanstreamPaymentTableMap::ORDER_ID, $orderId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BeanstreamPaymentTableMap::ORDER_ID, $orderId, $comparison);
    }

    /**
     * Filter the query on the message_id column
     *
     * Example usage:
     * <code>
     * $query->filterByMessageId(1234); // WHERE message_id = 1234
     * $query->filterByMessageId(array(12, 34)); // WHERE message_id IN (12, 34)
     * $query->filterByMessageId(array('min' => 12)); // WHERE message_id > 12
     * </code>
     *
     * @param     mixed $messageId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBeanstreamPaymentQuery The current query, for fluid interface
     */
    public function filterByMessageId($messageId = null, $comparison = null)
    {
        if (is_array($messageId)) {
            $useMinMax = false;
            if (isset($messageId['min'])) {
                $this->addUsingAlias(BeanstreamPaymentTableMap::MESSAGE_ID, $messageId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($messageId['max'])) {
                $this->addUsingAlias(BeanstreamPaymentTableMap::MESSAGE_ID, $messageId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(BeanstreamPaymentTableMap::MESSAGE_ID, $messageId, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%'); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildBeanstreamPaymentQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $message)) {
                $message = str_replace('*', '%', $message);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(BeanstreamPaymentTableMap::MESSAGE, $message, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildBeanstreamPayment $beanstreamPayment Object to remove from the list of results
     *
     * @return ChildBeanstreamPaymentQuery The current query, for fluid interface
     */
    public function prune($beanstreamPayment = null)
    {
        if ($beanstreamPayment) {
            $this->addUsingAlias(BeanstreamPaymentTableMap::ID, $beanstreamPayment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the beanstream_payment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BeanstreamPaymentTableMap::DATABASE_NAME);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            BeanstreamPaymentTableMap::clearInstancePool();
            BeanstreamPaymentTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $affectedRows;
    }

    /**
     * Performs a DELETE on the database, given a ChildBeanstreamPayment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or ChildBeanstreamPayment object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *         rethrown wrapped into a PropelException.
     */
     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(BeanstreamPaymentTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(BeanstreamPaymentTableMap::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();


        BeanstreamPaymentTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            BeanstreamPaymentTableMap::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

} // BeanstreamPaymentQuery
