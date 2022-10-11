import sys
#import location.point as lp
import location.test as lt

def send():
     account = sys.argv[1]
    # password = sys.argv[2]
     latitude = sys.argv[2]
     longitude = sys.argv[3]
     mode = sys.argv[4]
     #place = lp.search(eval(longitude),eval(latitude))
     data = {}
    # data[account] = 'password is ' + str(password)
     data['accidents'] = lt.accidents()
     data['rals'] = lt.rals()
     data['todatdata'] = lt.todaydata()
     data['tomorrow'] = lt.tomorrow()
     #data['place'] = str(place)
     data['mode'] = mode
     print(data)
     
   

if __name__ == '__main__':
    send()