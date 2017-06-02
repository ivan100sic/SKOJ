import random

n = 100;
a = [0] * n;
for i in range(n):
	a[i] = random.randint(0, 1000000000)

print('n = {};'.format(n));
for i in range(n):
	print('a.{} = {};'.format(i, a[i]));

print();
a.sort();
print('k = 1;');
for i in range(n):
	print('!= a.{} {} {} k = 1; {}'.format(i, a[i], '{', '}'));
print('k { @ }');