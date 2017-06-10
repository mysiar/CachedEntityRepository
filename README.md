CachedEntityRepository
======================

Result cache entity repository with 4 find methods implemented.

app/config/config.yaml
```yaml
doctrine:
  orm:
    query_cache_driver:
      type: apcu
    metadata_cache_driver:
      type: apcu
    result_cache_driver:
      type: apcu
```
