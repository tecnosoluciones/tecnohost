<?php

namespace ACP\RequestHandler\Ajax;

use AC\IntegrationRepository;
use AC\Nonce;
use AC\Request;
use AC\RequestAjaxHandler;
use ACP\Settings\Option\IntegrationStatus;

class IntegrationToggle implements RequestAjaxHandler
{

    private $repository;

    public function __construct(IntegrationRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(): void
    {
        $request = new Request();

        if ( ! (new Nonce\Ajax())->verify($request)) {
            wp_send_json_error();
        }

        $integration = $this->repository->find_by_slug(
            $request->get('integration')
        );

        if ( ! $integration) {
            wp_send_json_error();
        }

        $option = new IntegrationStatus(
            $integration->get_slug()
        );

        $request->get('status')
            ? $option->set_active()
            : $option->set_inactive();

        wp_send_json_success();
    }

}