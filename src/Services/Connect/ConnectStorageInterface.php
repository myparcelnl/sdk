<?php

declare(strict_types=1);

namespace MyParcelNL\Sdk\Services\Connect;

use MyParcelNL\Sdk\Model\Connect\ConnectInstallation;
use MyParcelNL\Sdk\Model\Connect\ConnectNonces;
use MyParcelNL\Sdk\Model\Connect\ConnectToken;

/**
 * Where a shop's connect state is kept. The consumer writes this.
 *
 * Three records rather than one blob, because they do not last equally long. Each save method below
 * says where its record belongs and how long to keep it. Putting all three in one place works too:
 * the split is an offer, not a duty.
 *
 * Every record has toArray() and a static fromArray(), so an implementation is one line each way:
 *
 *     public function saveInstallation(ConnectInstallation $installation): void
 *     {
 *         update_option('myparcel_connect_installation', json_encode($installation->toArray()));
 *     }
 *
 *     public function loadInstallation(): ?ConnectInstallation
 *     {
 *         $raw = get_option('myparcel_connect_installation');
 *
 *         return $raw ? ConnectInstallation::fromArray(json_decode($raw, true)) : null;
 *     }
 *
 * The SDK encrypts the private key and the access token before they get here, so nothing you store is
 * a readable secret. The two getters that hold one say so in their names.
 */
interface ConnectStorageInterface
{
    /**
     * @return \MyParcelNL\Sdk\Model\Connect\ConnectInstallation|null Null when this shop has never
     *                                                               connected.
     */
    public function loadInstallation(): ?ConnectInstallation;

    /**
     * Keep this for as long as the shop exists.
     *
     * It holds the key pair MyParcel identifies the shop by, so durable configuration is the only
     * right home: wp_options, PrestaShop Configuration, your own table. Never a cache with a
     * lifetime. If this is lost, the shop becomes a new shop to MyParcel and the merchant has to
     * reconnect.
     */
    public function saveInstallation(ConnectInstallation $installation): void;

    /**
     * @return \MyParcelNL\Sdk\Model\Connect\ConnectToken|null Null when there is no usable token.
     */
    public function loadToken(): ?ConnectToken;

    /**
     * Keep this until the shop disconnects. It is replaced on every refresh.
     *
     * The same place as the installation is fine, and so is a cache with a lifetime: nothing here
     * outlives the token, so losing it costs one refresh and never a reconnect.
     *
     * @param \MyParcelNL\Sdk\Model\Connect\ConnectToken|null $token Null means delete the record.
     */
    public function saveToken(?ConnectToken $token): void;

    /**
     * @return \MyParcelNL\Sdk\Model\Connect\ConnectNonces|null
     */
    public function loadNonces(): ?ConnectNonces;

    /**
     * Keep this briefly. Give it a lifetime of about an hour, or clear it on a schedule.
     *
     * One record in one place. It holds two nonces that come from different places, but they need
     * the same treatment, so there is nothing to route and nothing to tell apart: store the array as
     * it arrives. If you do look inside it, the keys are `startNonce` and `dpopNonce`.
     *
     * Not a PHP session, even though this is the shortest lived of the three. The SDK refreshes the
     * access token whenever it has expired, and that happens in cron jobs and webhook handlers where
     * there is no session, so the nonce would be missing exactly where a refresh happens most. An
     * option row, a cache entry or a transient all work, because a background request can read them.
     *
     * Safe to lose: the SDK asks MyParcel for a new nonce and carries on, at one extra round trip.
     * Keeping a stale one costs the same. So never store it permanently, and never worry about it.
     *
     * @param \MyParcelNL\Sdk\Model\Connect\ConnectNonces|null $nonces Null means delete the record.
     */
    public function saveNonces(?ConnectNonces $nonces): void;

    /**
     * Remove all three records.
     *
     * This is for uninstalling the plugin: it destroys the key pair, and with it the shop's identity
     * at MyParcel. ConnectService::disconnect() never calls it; ConnectService::uninstall() does.
     */
    public function clear(): void;
}
