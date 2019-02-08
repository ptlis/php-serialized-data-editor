<?php declare(strict_types=1);

/**
 * @copyright   (c) 2019-present brian ridley
 * @author      brian ridley <ptlis@ptlis.net>
 * @license     http://opensource.org/licenses/MIT MIT
 */

namespace ptlis\SerializedDataEditor\TypeFragment;

/**
 * Interface that array elements must implement.
 */
interface ArrayElement
{
    public function __toString(): string ;
}