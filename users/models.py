from django.db import models
from django.utils import timezone
from django.contrib.auth.models import User

class UserProfile(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	is_administrator = models.BooleanField(default=False)
	is_operator = models.BooleanField(default=False)
	is_student = models.BooleanField(default=False)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1]

class StudentProfile(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	k_f = models.CharField(max_length=8)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1] + '. (' + self.k_f + ')' 


class StudentFile1(models.Model):
	user = models.OneToOneField(User,on_delete=models.CASCADE)
	file1 = models.FileField(upload_to='students/file1/',null=True, blank=True)
	file1_date_uploaded = models.DateField(default=timezone.now, null=True, blank=True)

	def __str__(self):
		return self.user.last_name + ' ' + self.user.first_name[:1] + '. (' + self.file1.url + ')' 

	def delete(self, *args, **kwargs):
		self.file1.delete()
		self.file1_date_uploaded = None
		super().delete(*args, **kwargs)