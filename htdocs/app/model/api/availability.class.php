<?php

namespace Model\Api;
use Model\Base\Api;
use Model\Mapper\Cache;

/**
 * Availability API
 */
class Availability extends Api {

    const API_FIND_FILM = 'http://www.canistream.it/services/search?movieName={name}';
    const API_RENTAL_AVAILABILITY = 'http://www.canistream.it/services/query?movieId={id}&attributes=1&mediaType=rental';

    public function get($filmName) {
        $json = $this->callJson('find.film', ['name' => urlencode($filmName)], Cache::EXPIRE_WEEK);
        if (!empty($json) and count($json) > 0 and !empty($json[0]) and !empty($json[0]->_id)) {
            $id = $json[0]->_id;
            $rental = $this->callJson('rental.availability', ['id' => $id], Cache::EXPIRE_WEEK);
            return empty($rental) ? null : $rental;
        }
        return null;
    }

}
