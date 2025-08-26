# Demo for k8s

```bash
kubectl apply -f .
# or
kubectl apply -f webapp-configmap.yaml
kubectl apply -f nginx-php-deployment.yaml
kubectl apply -f nginx-php-service.yaml

kubectl get pods
kubectl get svc
kubectl get configmap

kubectl port-forward svc/webapp-svc 8080:80
```


debug
```bash
kubectl describe pod webapp-576b8658f9-ccrtx

# see log
kubectl logs pod/webapp-576b8658f9-ccrtx -c php
kubectl logs pod/webapp-576b8658f9-ccrtx -c nginx

# login container
kubectl exec -it webapp-576b8658f9-ccrtx -c php -- bash
kubectl exec -it webapp-576b8658f9-ccrtx -c nginx -- bash
```

## Delete
```bash
kubectl delete -f .
# or
kubectl delete -f nginx-php-deployment.yaml
kubectl delete -f nginx-php-service.yaml
kubectl delete -f webapp-configmap.yaml

```
