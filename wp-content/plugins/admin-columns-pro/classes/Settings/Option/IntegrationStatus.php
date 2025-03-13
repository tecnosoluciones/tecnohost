<?php

namespace ACP\Settings\Option;

use AC\Settings\Option;

class IntegrationStatus extends Option
{

    public function __construct(string $integration_slug)
    {
        parent::__construct('integration_' . $integration_slug);
    }

    public function set_active(): void
    {
        $this->delete();
    }

    public function set_inactive(): void
    {
        $this->save('inactive');
    }

    public function is_active(): bool
    {
        return 'inactive' !== $this->get();
    }

}