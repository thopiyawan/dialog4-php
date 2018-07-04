<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/monitoring/v3/alert_service.proto

namespace Google\Cloud\Monitoring\V3;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * The protocol for the `UpdateAlertPolicy` request.
 *
 * Generated from protobuf message <code>google.monitoring.v3.UpdateAlertPolicyRequest</code>
 */
class UpdateAlertPolicyRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Optional. A list of alerting policy field names. If this field is not
     * empty, each listed field in the existing alerting policy is set to the
     * value of the corresponding field in the supplied policy (`alert_policy`),
     * or to the field's default value if the field is not in the supplied
     * alerting policy.  Fields not listed retain their previous value.
     * Examples of valid field masks include `display_name`, `documentation`,
     * `documentation.content`, `documentation.mime_type`, `user_labels`,
     * `user_label.nameofkey`, `enabled`, `conditions`, `combiner`, etc.
     * If this field is empty, then the supplied alerting policy replaces the
     * existing policy. It is the same as deleting the existing policy and
     * adding the supplied policy, except for the following:
     * +   The new policy will have the same `[ALERT_POLICY_ID]` as the former
     *     policy. This gives you continuity with the former policy in your
     *     notifications and incidents.
     * +   Conditions in the new policy will keep their former `[CONDITION_ID]` if
     *     the supplied condition includes the `name` field with that
     *     `[CONDITION_ID]`. If the supplied condition omits the `name` field,
     *     then a new `[CONDITION_ID]` is created.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 2;</code>
     */
    private $update_mask = null;
    /**
     * Required. The updated alerting policy or the updated values for the
     * fields listed in `update_mask`.
     * If `update_mask` is not empty, any fields in this policy that are
     * not in `update_mask` are ignored.
     *
     * Generated from protobuf field <code>.google.monitoring.v3.AlertPolicy alert_policy = 3;</code>
     */
    private $alert_policy = null;

    public function __construct() {
        \GPBMetadata\Google\Monitoring\V3\AlertService::initOnce();
        parent::__construct();
    }

    /**
     * Optional. A list of alerting policy field names. If this field is not
     * empty, each listed field in the existing alerting policy is set to the
     * value of the corresponding field in the supplied policy (`alert_policy`),
     * or to the field's default value if the field is not in the supplied
     * alerting policy.  Fields not listed retain their previous value.
     * Examples of valid field masks include `display_name`, `documentation`,
     * `documentation.content`, `documentation.mime_type`, `user_labels`,
     * `user_label.nameofkey`, `enabled`, `conditions`, `combiner`, etc.
     * If this field is empty, then the supplied alerting policy replaces the
     * existing policy. It is the same as deleting the existing policy and
     * adding the supplied policy, except for the following:
     * +   The new policy will have the same `[ALERT_POLICY_ID]` as the former
     *     policy. This gives you continuity with the former policy in your
     *     notifications and incidents.
     * +   Conditions in the new policy will keep their former `[CONDITION_ID]` if
     *     the supplied condition includes the `name` field with that
     *     `[CONDITION_ID]`. If the supplied condition omits the `name` field,
     *     then a new `[CONDITION_ID]` is created.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 2;</code>
     * @return \Google\Protobuf\FieldMask
     */
    public function getUpdateMask()
    {
        return $this->update_mask;
    }

    /**
     * Optional. A list of alerting policy field names. If this field is not
     * empty, each listed field in the existing alerting policy is set to the
     * value of the corresponding field in the supplied policy (`alert_policy`),
     * or to the field's default value if the field is not in the supplied
     * alerting policy.  Fields not listed retain their previous value.
     * Examples of valid field masks include `display_name`, `documentation`,
     * `documentation.content`, `documentation.mime_type`, `user_labels`,
     * `user_label.nameofkey`, `enabled`, `conditions`, `combiner`, etc.
     * If this field is empty, then the supplied alerting policy replaces the
     * existing policy. It is the same as deleting the existing policy and
     * adding the supplied policy, except for the following:
     * +   The new policy will have the same `[ALERT_POLICY_ID]` as the former
     *     policy. This gives you continuity with the former policy in your
     *     notifications and incidents.
     * +   Conditions in the new policy will keep their former `[CONDITION_ID]` if
     *     the supplied condition includes the `name` field with that
     *     `[CONDITION_ID]`. If the supplied condition omits the `name` field,
     *     then a new `[CONDITION_ID]` is created.
     *
     * Generated from protobuf field <code>.google.protobuf.FieldMask update_mask = 2;</code>
     * @param \Google\Protobuf\FieldMask $var
     * @return $this
     */
    public function setUpdateMask($var)
    {
        GPBUtil::checkMessage($var, \Google\Protobuf\FieldMask::class);
        $this->update_mask = $var;

        return $this;
    }

    /**
     * Required. The updated alerting policy or the updated values for the
     * fields listed in `update_mask`.
     * If `update_mask` is not empty, any fields in this policy that are
     * not in `update_mask` are ignored.
     *
     * Generated from protobuf field <code>.google.monitoring.v3.AlertPolicy alert_policy = 3;</code>
     * @return \Google\Cloud\Monitoring\V3\AlertPolicy
     */
    public function getAlertPolicy()
    {
        return $this->alert_policy;
    }

    /**
     * Required. The updated alerting policy or the updated values for the
     * fields listed in `update_mask`.
     * If `update_mask` is not empty, any fields in this policy that are
     * not in `update_mask` are ignored.
     *
     * Generated from protobuf field <code>.google.monitoring.v3.AlertPolicy alert_policy = 3;</code>
     * @param \Google\Cloud\Monitoring\V3\AlertPolicy $var
     * @return $this
     */
    public function setAlertPolicy($var)
    {
        GPBUtil::checkMessage($var, \Google\Cloud\Monitoring\V3\AlertPolicy::class);
        $this->alert_policy = $var;

        return $this;
    }

}

