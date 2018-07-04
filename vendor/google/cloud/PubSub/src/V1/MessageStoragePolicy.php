<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/pubsub/v1/pubsub.proto

namespace Google\Cloud\PubSub\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>google.pubsub.v1.MessageStoragePolicy</code>
 */
class MessageStoragePolicy extends \Google\Protobuf\Internal\Message
{
    /**
     * The list of GCP regions where messages that are published to the topic may
     * be persisted in storage. Messages published by publishers running in
     * non-allowed GCP regions (or running outside of GCP altogether) will be
     * routed for storage in one of the allowed regions. An empty list indicates a
     * misconfiguration at the project or organization level, which will result in
     * all Publish operations failing.
     *
     * Generated from protobuf field <code>repeated string allowed_persistence_regions = 1;</code>
     */
    private $allowed_persistence_regions;

    public function __construct() {
        \GPBMetadata\Google\Pubsub\V1\Pubsub::initOnce();
        parent::__construct();
    }

    /**
     * The list of GCP regions where messages that are published to the topic may
     * be persisted in storage. Messages published by publishers running in
     * non-allowed GCP regions (or running outside of GCP altogether) will be
     * routed for storage in one of the allowed regions. An empty list indicates a
     * misconfiguration at the project or organization level, which will result in
     * all Publish operations failing.
     *
     * Generated from protobuf field <code>repeated string allowed_persistence_regions = 1;</code>
     * @return \Google\Protobuf\Internal\RepeatedField
     */
    public function getAllowedPersistenceRegions()
    {
        return $this->allowed_persistence_regions;
    }

    /**
     * The list of GCP regions where messages that are published to the topic may
     * be persisted in storage. Messages published by publishers running in
     * non-allowed GCP regions (or running outside of GCP altogether) will be
     * routed for storage in one of the allowed regions. An empty list indicates a
     * misconfiguration at the project or organization level, which will result in
     * all Publish operations failing.
     *
     * Generated from protobuf field <code>repeated string allowed_persistence_regions = 1;</code>
     * @param string[]|\Google\Protobuf\Internal\RepeatedField $var
     * @return $this
     */
    public function setAllowedPersistenceRegions($var)
    {
        $arr = GPBUtil::checkRepeatedField($var, \Google\Protobuf\Internal\GPBType::STRING);
        $this->allowed_persistence_regions = $arr;

        return $this;
    }

}

